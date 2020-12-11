<?php

namespace App\Http\Resources\User;


use App\Http\Resources\PrivacySettings;
use Illuminate\Http\Resources\Json\JsonResource;

class SimpleUser extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'isOnline' => $this->isOnline,
            'lastActivity' => $this->last_activity_dt,
            'profile' => new Profile($this->profile),
        ];
    }
}


