<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class PrivacyRole
{
    public function handle($request, Closure $next, $privacyPermission)
    {
        $userId = $request->route()->parameter('id', 0);
        $user = User::find($userId);
        if ($user instanceof User) {
            $privacySettingPermissionRole = $user->privacySettings->role($privacyPermission)->first();
            $userPrivacyRole = $user->getUserPrivacyRole(\Auth::user());
            if($privacySettingPermissionRole && $userPrivacyRole) {
                if ((int) $privacySettingPermissionRole->priority > (int) $userPrivacyRole->priority && $userId !== $user->id) {
                    return response()->json(['message' => 'Not allowed'], 403);
                }
            }
        } else {
            return response()->json(['message' => 'Данный пользователь не найден'], 404);
        }
        return $next($request);
    }
}
