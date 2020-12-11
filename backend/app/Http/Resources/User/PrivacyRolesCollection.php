<?php

namespace App\Http\Resources\User;


use App\Http\Resources\PrivacySettings;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PrivacyRolesCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'list' => $this->collection->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'display_name' => $role->display_name,
                    'description' => $role->description,
                ];
            }),
        ];
    }
}


