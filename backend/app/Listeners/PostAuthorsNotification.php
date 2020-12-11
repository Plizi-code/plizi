<?php


namespace App\Listeners;

use App\Models\Notification;
use App\Models\Post;
use App\Models\User;
use App\Notifications\UserSystemNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class PostAuthorsNotification implements ShouldQueue
{
    use Queueable;

    const POST_LIKED = 'post.liked';

    /**
     * @param $event
     * @param $data
     */
    public function handle($event, $data)
    {
        $post_id = isset($data['post_id']) && $data['post_id'] ? $data['post_id'] : null;
        $user_id = isset($data['user_id']) && $data['user_id'] ? $data['user_id'] : null;
        $user = User::find($user_id);
        $post = Post::with('author')->find($post_id);
        $details = $this->preparePayload($event, $user, $post);
        $isNotificationExists = Notification::where('notifiable_type', User::class)
            ->where('notifiable_id', $post->author->id)
            ->where('data->data->sender->id', $user->id)
            ->where('data->data->post->id', $post->id)
            ->exists();

        if($post->author && !$isNotificationExists) {
            $post->author->notify(new UserSystemNotifications($details));
        }
    }

    /**
     * @param $event
     * @param $user
     * @param $post
     * @return array|null
     */
    private function preparePayload($event, $user, $post)
    {
        if($event === self::POST_LIKED) {
            return [
                'sender' => [
                    'firstName' => $user->profile->first_name,
                    'lastName' => $user->profile->last_name,
                    'sex' => $user->profile->sex,
                    'userPic' => $user->profile->user_pic,
                    'lastActivity' => $user->last_activity_dt,
                    'id' => $user->id
                ],
                'post' => [
                    'id' => $post->id
                ],
                'body' => 'User {0, string} liked your post',
                'notificationType' => $event,
            ];
        }
        return null;
    }
}
