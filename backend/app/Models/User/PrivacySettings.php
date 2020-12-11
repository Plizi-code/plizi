<?php

namespace App\Models\User;

use App\Models\Rbac\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Spiritix\LadaCache\Database\LadaCacheTrait;

class PrivacySettings extends Model
{
    use LadaCacheTrait;
    const TWO_FACTOR_AUTH_ENABLED_DEFAULT = 0;
    const TWO_FACTOR_AUTH_ENABLED_FALSE = 0;
    const TWO_FACTOR_AUTH_ENABLED_TRUE = 0;

    const SMS_CONFIRM_DEFAULT = 0;
    const SMS_CONFIRM_DISABLED = 0;
    const SMS_CONFIRM_ENABLED = 0;

    protected $table = 'users_privacy_settings';

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected $fillable = [
        'page_type',
        'write_messages_permissions',
        'post_wall_permissions',
        'view_wall_permissions',
        'view_friends_permissions',
        'two_factor_auth_enabled',
        'sms_confirm',
    ];

    public static function rules($keys = [])
    {
        $rules = [
            'user_id' => 'required|integer',
            'page_type' => Rule::exists('rbac_roles', 'id') . '|integer|nullable',
            'write_messages_permissions' => Rule::exists('rbac_roles', 'id') . '|integer|nullable',
            'post_wall_permissions' => Rule::exists('rbac_roles', 'id') . '|integer|nullable',
            'view_wall_permissions' => Rule::exists('rbac_roles', 'id') . '|integer|nullable',
            'view_friends_permissions' => Rule::exists('rbac_roles', 'id') . '|integer|nullable',
            'two_factor_auth_enabled' => 'integer|nullable|min:0|max:1',
            'sms_confirm' => 'integer|nullable|min:0|max:1',
        ];

        if (count($keys)) {
            return array_filter($rules, function ($index) use ($keys) {
                return in_array($index, $keys);

            }, ARRAY_FILTER_USE_KEY);
        }

        return $rules;
    }

    public function getDateFormat()
    {
        return 'U';
    }

    public function role($field)
    {
        return $this->hasOne(Role::class, 'id', $field);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
