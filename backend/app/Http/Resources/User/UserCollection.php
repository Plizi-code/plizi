<?php

namespace App\Http\Resources\User;


use App\Http\Resources\PrivacySettings;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{

    /**
     * @var int
     */
    protected $totalCount;

    public function __construct($resource, $total_count = 0)
    {
        $this->totalCount = $total_count;
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'list' => $this->collection->map(static function ($user) {
                /** @var \App\Models\User $user */
                if(Auth::user()->id === $user->id) {
                    return [
                        'id' => $user->id,
                        'email' => $user->email,
                        'isOnline' => $user->isOnline,
                        'lastActivity' => $user->last_activity_dt,
                        'profile' => new Profile($user->profile),
                        'privacySettings' => new PrivacySettings($user->privacySettings)
                    ];
                }

                $friendship = $user->getFriendship(Auth::user());
                return [
                    'id' => $user->id,
                    'isOnline' => $user->isOnline,
                    'lastActivity' => $user->last_activity_dt,
                    'profile' => new Profile($user->profile),
                    'mutualFriendsCount' => (int)$user->mutual_count,
                    'friendshipSinceTime' => $friendship ? $friendship->created_at->getTimestamp() : null,
                ];
            }),
            'totalCount' => $this->totalCount
        ];
    }
}


