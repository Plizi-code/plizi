<?php


namespace App\Services;

use App\Models\User;
use App\Models\LinkedSocialAccount;
use Laravel\Socialite\Two\User as ProviderUser;

class SocialAccountsService
{
    /**
     * Find or create user instance by provider user instance and provider name.
     *
     * @param ProviderUser $providerUser
     * @param string $provider
     *
     * @return User
     */
    public function findOrCreate(ProviderUser $providerUser, string $provider): User
    {
        $linkedSocialAccount = LinkedSocialAccount::where('provider_name', $provider)
            ->where('provider_id', $providerUser->getId())
            ->first();

        if ($linkedSocialAccount) {
            return $linkedSocialAccount->user;
        } else {
            $user = null;

            if ($email = $providerUser->getEmail()) {
                $user = User::where('email', $email)->first();
            }

            if (!$user) {
                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'username' => $providerUser->getNickname(),
                ]);
                $user->profile()->create([
                    'first_name' => isset($providerUser->user['first_name']) ? $providerUser->user['first_name'] : $providerUser->getName(),
                    'last_name' => isset($providerUser->user['last_name']) ? $providerUser->user['last_name'] : $providerUser->getName(),
                    'user_pic' => isset($providerUser->user['photo']) ? $providerUser->user['photo'] : $providerUser->getAvatar(),
                ]);
                $user->refresh();
            }

            $user->linkedSocialAccounts()->create([
                'provider_id' => $providerUser->getId(),
                'provider_name' => $provider,
            ]);

            return $user;
        }
    }
}
