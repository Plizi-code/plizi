<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\PrivacyRolesCollection;
use App\Models\Rbac\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\User\User as UserResource;

class UserPrivacySettingController extends Controller
{

    /**
     * Patch user privacy settings api method.
     *
     * @param Request $request
     * @return UserResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function patch(Request $request)
    {
        $this->validate($request, User\PrivacySettings::rules($request->keys()));
        $user = User::find(Auth::user()->id);

        if (!User\PrivacySettings::where('user_id', $user->id)->exists()) {
            $user->privacySettings()->create(['user_id' => $user->id]);
        }

        $user->privacySettings()->update(array_filter([
            'page_type' => $request->pageType,
            'write_messages_permissions' => $request->writeMessagesPermissions,
            'post_wall_permissions' => $request->postWallPermissions,
            'view_wall_permissions' => $request->viewWallPermissions,
            'view_friends_permissions' => $request->viewFriendsPermissions,
            'two_factor_auth_enabled' => $request->twoFactorAuthEnabled,
            'sms_confirm' => $request->smsConfirm,
        ], function ($var) {
            return $var !== false && !is_null($var) && ($var != '' || $var == '0');
        }));

        return new UserResource($user->fresh());
    }

    public function roles()
    {
        return new PrivacyRolesCollection(Role::all());
    }
}
