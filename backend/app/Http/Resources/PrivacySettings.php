<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PrivacySettings extends JsonResource
{
    public function toArray($request)
    {
        return [
            'pageType' => $this->page_type,
            'writeMessagesPermissions' => $this->write_messages_permissions,
            'postWallPermissions' => $this->post_wall_permissions,
            'viewWallPermissions' => $this->view_wall_permissions,
            'viewFriendsPermissions' => $this->view_friends_permissions,
            'twoFactorAuthEnabled' => $this->two_factor_auth_enabled,
            'smsConfirm' => $this->sms_confirm,
        ];
    }
}
