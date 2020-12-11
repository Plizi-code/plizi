<?php


namespace App\Listeners\Friendships;

use App\Http\Resources\User\SimpleUser;
use App\Notifications\UserSystemNotifications;

class FriendshipNotification
{

    /**
     * @param $event
     * @param $users
     */
    public function handle($event, $users)
    {
        [$sender, $recipient] = $users;

        $details = [
            'sender' => [
                'firstName' => $sender->profile->first_name,
                'lastName' => $sender->profile->last_name,
                'sex' => $sender->profile->sex,
                'userPic' => $sender->profile->user_pic,
                'lastActivity' => $sender->last_activity_dt,
                'id' => $sender->id
            ],
            'user' => new SimpleUser($sender),
            'body' => 'User {0, string} sent you friend request',
            'notificationType' => $event,
        ];
        $recipient->notify(new UserSystemNotifications($details));
    }
}
