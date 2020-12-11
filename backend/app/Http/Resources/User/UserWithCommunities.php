<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Community\CommunityCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class UserWithCommunities extends JsonResource
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
            'communities' => new CommunityCollection($this->communities),
            'profile' => new Profile($this->profile)
        ];
    }
}


