<?php


namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Cache\Repository;


class UserProviderDecorator implements UserProvider
{
    /**
     * @var UserProvider
     */
    private $provider;

    /**
     * @var Repository
     */
    private $cache;

    public function __construct(UserProvider $provider, Repository $cache)
    {
        $this->provider = $provider;
        $this->cache = $cache;
    }

    /**
     * {@inheritDoc}
     */
    public function retrieveById($identifier)
    {
        return $this->cache->remember('id-' . $identifier, 60, function () use ($identifier) {
            return $this->provider->retrieveById($identifier);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function retrieveByToken($identifier, $token)
    {
        return $this->provider->retrieveById($identifier, $token);
    }

    /**
     * {@inheritDoc}
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        return $this->provider->updateRememberToken($user, $token);
    }

    /**
     * {@inheritDoc}
     */
    public function retrieveByCredentials(array $credentials)
    {
        return $this->provider->retrieveByCredentials($credentials);
    }

    /**
     * {@inheritDoc}
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return $this->provider->validateCredentials($user, $credentials);
    }
}
