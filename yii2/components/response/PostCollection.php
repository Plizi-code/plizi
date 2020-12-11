<?php
namespace app\components\response;

use app\models\Communities;
use app\models\Posts;
use app\models\PostsQuery;
use app\models\Users;
use Yii;

class PostCollection
{
    /**
     * @var PostsQuery
     */
    private $query;

    private static $postables = [];

    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * @param $userId
     * @return array
     */
    public function forNews($userId)
    {
        $response = [];
        /** @var Posts $post */
        foreach ($this->query->each() as $post) {
            $postable = $this->getPostable($post);
            if ($postable instanceof Users) {
                $response[] = $this->forNewsFromUser($post, $userId);
            }
            if ($postable instanceof Communities) {
                $response[] = $this->forNewsFromCommunity($post, $userId);
            }
        }

        return $response;
    }

    private function getPostable(Posts $post)
    {
        $key = $post->postable_type . $post->postable_id;
        if (!array_key_exists($key, self::$postables)) {
            self::$postables[$key] = $post->postable;
        }
        return self::$postables[$key];
    }

    /**
     * @param Posts $post
     * @param $userId
     * @return array
     */
    private function forNewsFromUser($post, $userId): array
    {
        $response = $this->forNewsFrom($post, $userId);
        $response['user'] = (new SimpleUser)($this->getPostable($post));
        return $response;
    }

    /**
     * @param Posts $post
     * @param $userId
     * @return array
     */
    private function forNewsFrom($post, $userId): array
    {
        $author = Yii::$app->cache->getOrSet([
            'author_with_profile',
            'user_id' => $post->author_id
        ], static function () use ($post) {
            return $post->getAuthor()->joinWith(['profile'])->one();
        }, 300);
        return [
            'id' => $post->id,
            'name' => $post->name,
            'body' => strip_tags($post->body, '<span><p>'),
            'primaryImage' => $post->primary_image,
            'likes' => $post->likes,
            'usersLikes' => $post->likess ? (new SimpleUsers)($post->usersLikes) : [],
            'views' => $post->views,
            'sharesCount' => $post->getChildren()->cache(300)->count(),
            'commentsCount' => $post->getCommentsCount(),
            'alreadyLiked' => $post->getLike($userId)->exists(),
            'alreadyViewed' => $post->getAlreadyViewed($userId)->exists(),
            'attachments' => (new AttachmentsCollection)($post->attachments),
            'createdAt' => $post->created_at,
//            'sharedFrom' => $post->parent_id
//                ? new Post($post->parent()->withCount('comments', 'children')->first())
//                : null,
            'author' => (new SimpleUser())($author),
        ];
    }

    /**
     * @param Posts $post
     * @param $userId
     * @return array
     */
    private function forNewsFromCommunity($post, $userId): array
    {
        $response = $this->forNewsFrom($post, $userId);
        $response['community'] = (new Community)($this->getPostable($post));
        return $response;
    }
}
