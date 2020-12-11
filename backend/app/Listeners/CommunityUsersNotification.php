<?php


namespace App\Listeners;

use App\Models\Community;
use App\Models\Post;
use App\Notifications\UserSystemNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class CommunityUsersNotification implements ShouldQueue
{
    use Queueable;

    const COMMUNITY_POST_CREATED = 'community.post.created';

    /**
     * @param $event
     * @param $community
     * @param $post
     */
    public function handle($event, $data)
    {
        $community = isset($data['community']) && $data['community'] ? $data['community'] : null;
        $post = isset($data['post']) && $data['post'] ? $data['post'] : null;
        $details = $this->preparePayload($event, $community, $post);
        if($details) {
            foreach ($community->subscribers as $user) {
                $user->notify(new UserSystemNotifications($details));
            }
        }
    }

    /**
     * @param $event
     * @param Community $community
     * @param Post $post
     * @return array|null
     */
    private function preparePayload($event, $community, $post)
    {
        if($event === self::COMMUNITY_POST_CREATED) {
            return [
                'community' => [
                    'name' => $community->name,
                    'primaryImage' => $community->avatar
                        ? $community->avatar->s3_thumb_url
                        : $community->primary_image,
                    'id' => $community->id,
                    'postId' => $post->id,
                    'postName' => Str::limit(strip_tags($post->body), 30),
                ],
                'body' => 'There is new post in community {0, string}',
                'notificationType' => $event,
            ];
        }
        return null;
    }
}
