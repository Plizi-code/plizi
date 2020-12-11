<?php

namespace App\Http\Resources\User;


use App\Http\Resources\PrivacySettings;
use App\Models\User\Blacklisted;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{

    public $appendMutual;

    public function __construct($resource, $appendMutual = true)
    {
        parent::__construct($resource);
        $this->appendMutual = $appendMutual;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if(\Auth::user()->id === $this->id) {
            return [
                'id' => $this->id,
                'email' => $this->email,
                'isOwner' => true,
                'isOnline' => $this->isOnline,
                'lastActivity' => $this->last_activity_dt,
                'profile' => new Profile($this->profile),
                'privacySettings' => new PrivacySettings($this->privacySettings),
                'stats' => [
                    'notificationsCount' => $this->notificationsCount,
                    'unreadMessagesCount' => $this->unreadMessagesCount,
                    'pendingFriendshipRequestsCount' => $this->pendingFriendshipRequestsCount,
                    'totalFriendsCount' => $this->totalFriendsCount,
                    'followCount' => $this->profile->follower_count,
                    'videosCount' => $this->profile->video_count,
                    'imageCount' => $this->profile->image_count,
                ],
            ];
        }

        $data = [
            'id' => $this->id,
            'isOwner' => false,
            'isOnline' => $this->isOnline,
            'lastActivity' => $this->last_activity_dt,
            'profile' => new Profile($this->profile),
            'privacySettings' => new PrivacySettings($this->privacySettings),
            'stats' => [
                'totalFriendsCount' => $this->totalFriendsCount,
                'followCount' => $this->profile->follower_count,
                'videosCount' => $this->profile->video_count,
                'isFollow' => $this->isFollow,
                'isFriend' => $this->isFriendWith(auth()->user()),
                'isInBlacklist' => Blacklisted::where([
                    'user_id' => auth()->id(),
                    'blacklisted_id' => $this->id,
                ])->exists(),
                'imageCount' => $this->profile->image_count,
            ],
        ];
        if($this->appendMutual) {
            $data['mutualFriendsCount'] = (int)$this->profile->mutual;
        }

        return $data;
    }
}


