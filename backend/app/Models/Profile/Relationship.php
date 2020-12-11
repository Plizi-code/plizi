<?php

namespace App\Models\Profile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Relationship extends Model
{

    protected $table = 'profile_relationships';

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected $fillable = [
        'title',
    ];

    public static function rules($keys = [])
    {
        $rules = [
            'title' => 'required|string|min:1|max:255',
        ];

        if (count($keys)) {
            return array_filter($rules, function ($index) use ($keys) {
                return in_array($index, $keys);

            }, ARRAY_FILTER_USE_KEY);
        }

        return $rules;
    }
}
