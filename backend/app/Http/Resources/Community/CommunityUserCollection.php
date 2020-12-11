<?php

namespace App\Http\Resources\Community;

use App\Http\Resources\User\Profile;
use App\Models\CommunityMember;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommunityUserCollection extends ResourceCollection
{
    /**
     * @var CommunityMember
     */
    private $role;

    public function __construct($resource, $role = null)
    {
        parent::__construct($resource);
        $this->role = $role;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'role' => $this->role ? $this->role->role : null,
            'list' => $this->collection->map(function($user) {
                return [
                    'id' => $user->id,
                    'isOnline' => $user->isOnline,
                    'role' => $user->pivot ? $user->pivot->role : null,
                    'profile' => new Profile($user->profile)
                ];
            }),
        ];
    }
}
