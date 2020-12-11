<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SimpleUsers extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'list' => $this->collection->map(function ($user) {
                return [
                    'id' => $user->id,
                    'email' => $user->email,
                    'isOnline' => $user->isOnline,
                    'lastActivity' => $user->last_activity_dt,
                    'profile' => new Profile($user->profile),
                ];
            }),
        ];
    }
}
