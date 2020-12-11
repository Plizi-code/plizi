<?php


namespace App\Listeners\Friendships;

use App\Events\UserUpdated;
use App\Models\User;
use App\Notifications\UserSystemNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyFriends implements ShouldQueue
{
    use Queueable;

    const PROFILE_IMAGE_UPDATE = 'user.profile.image.updated';
    const PROFILE_IMAGE_CREATE = 'user.profile.image.created';
    const USER_POST_CREATE = 'user.post.created';

    /**
     * @param $event
     * @param $user_id
     */
    public function handle($event, $data)
    {
        $user_id = isset($data['user_id']) && $data['user_id'] ? $data['user_id'] : null;
        $post = isset($data['post']) && $data['post'] ? $data['post'] : null;
        $user = User::find($user_id);
        $friends = $user->getFriends();
        $friends = User::with( 'profile')->whereIn('id', array_keys($friends))->get();
        $details = $this->preparePayload($event, $user, $post);
        if($details) {
            foreach ($friends as $friend) {
                $friend->notify(new UserSystemNotifications($details));
            }
        }
    }

    /**
     * @param $event
     * @param User $user
     * @param $post
     * @return array|null
     */
    private function preparePayload($event, $user, $post)
    {
        if ($event === self::PROFILE_IMAGE_CREATE || $event === self::PROFILE_IMAGE_UPDATE) {
            $user->profile->refresh();
            event(new UserUpdated($user));
            return [
                'sender' => [
                    'firstName' => $user->profile->first_name,
                    'lastName' => $user->profile->last_name,
                    'sex' => $user->profile->sex,
                    'userPic' => $user->profile->user_pic,
                    'lastActivity' => $user->last_activity_dt,
                    'id' => $user->id
                ],
                'body' => $event === self::PROFILE_IMAGE_CREATE ? 'User {0, string} uploaded new photo' : 'User {0, string} updated profile photo',
                'notificationType' => $event,
            ];
        }

        if($event === self::USER_POST_CREATE && $post) {
            return [
                'sender' => [
                    'firstName' => $user->profile->first_name,
                    'lastName' => $user->profile->last_name,
                    'sex' => $user->profile->sex,
                    'userPic' => $user->profile->user_pic,
                    'lastActivity' => $user->last_activity_dt,
                    'id' => $user->id,
                    'postId' => $post->id
                ],
                'body' => 'User {0, string} created new post',
                'notificationType' => 'user.post.created',
            ];
        }
        return null;
    }
}
