<?php

namespace App\Http\Resources\User;


class UserSearchCollection extends UserCollection
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
            'list' => $this->collection->map(function ($user) {
                return [
                    'id' => $user->id,
                    'lastActivity' => $user->last_activity_dt,
                    'isOnline' => $user->isOnline,
                    'profile' => new Profile($user->profile)
                ];
            }),
        ];
    }
}


