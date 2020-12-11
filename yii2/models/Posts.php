<?php

namespace app\models;

use app\components\traits\Morphed;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "posts".
 *
 * @property int $id
 * @property string|null $name
 * @property string $body
 * @property string|null $primary_image
 * @property int $likes
 * @property int $views
 * @property string $postable_id
 * @property string $postable_type
 * @property int $created_at
 * @property int $updated_at
 * @property int|null $parent_id
 * @property string|null $author_id
 * @property int|null $deleted_at
 *
 * @property PostAttachments[] $attachments
 * @property Likes[] $like
 * @property Likes[] $likess
 * @property Views[] $alreadyViewed
 * @property Users|Communities $postable
 * @property Users $author
 * @property self $parent
 * @property self[] $children
 * @property Users[] $usersLikes
 */
class Posts extends ActiveRecord
{
    use Morphed;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['body', 'postable_id', 'postable_type'], 'required'],
            [['body'], 'string'],
            [['likes', 'views', 'created_at', 'updated_at', 'parent_id', 'deleted_at'], 'integer'],
            [['name', 'primary_image', 'postable_id', 'postable_type'], 'string', 'max' => 191],
            [['author_id'], 'string', 'max' => 24],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'body' => 'Body',
            'primary_image' => 'Primary Image',
            'likes' => 'Likes',
            'views' => 'Views',
            'postable_id' => 'Postable ID',
            'postable_type' => 'Postable Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'parent_id' => 'Parent ID',
            'author_id' => 'Author ID',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * Gets query for [[PostAttachments]].
     *
     * @return ActiveQuery
     */
    public function getAttachments()
    {
        return $this->hasMany(PostAttachments::class, ['post_id' => 'id']);
    }

    public function getLike($ownerId)
    {
        return $this->hasMany(Likes::class, ['likeable_id' => 'id'])
            ->where([
                'user_id' => $ownerId,
                'likeable_type' => self::class,
            ]);
    }

    public function getAlreadyViewed($ownerId)
    {
        return $this->hasMany(Views::class, ['viewable_id' => 'id'])
            ->where([
                'user_id' => $ownerId,
                'viewable_type' => self::class,
            ]);
    }

    public function getPostable()
    {
        return $this->morphTo('postable');
    }

    public function getAuthor()
    {
        return $this->hasOne(Users::class, [
            'id' => 'author_id'
        ]);
    }

    public function getLikess()
    {
        return $this->hasMany(Likes::class, [
            'likeable_id' => 'id'
        ])
            ->where([
                'likeable_type' => "App\Models\Post",
            ]);
    }

    public function getUsersLikes()
    {
        return $this->hasMany(Users::class, ['id' => 'user_id'])
            ->via('likess')
            ->limit(8);
    }

    public function getParent() {
        return $this->hasOne( __CLASS__, [
            'id' => 'parent_id'
        ]);
    }

    public function getChildren()
    {
        return $this->hasMany(self::class, ['parent_id' => 'id']);
    }

    public function getCommentsCount()
    {
        return $this->hasMany(Comments::class, ['commentable_id' => 'id'])
            ->where([
                'reply_on' => null,
                'commentable_type' => 'App\\Models\\Post',
            ])
            ->cache(300)
            ->count();
    }

    /**
     * @param Users $user
     * @param int $limit
     * @param int $offset
     * @param bool $isMyPosts
     * @param bool $onlyLiked
     * @param null $orderBy
     * @param string $search
     * @param array $parts
     * @return PostsQuery
     */
    public static function getWithoutOldPosts(
        $user,
        $limit,
        $offset,
        $isMyPosts = false,
        $onlyLiked = false,
        $orderBy = null,
        $search = '',
        $parts = []
    ) {
        return self::find()
            ->own($user, $parts)
            ->communities($user, $parts)
            ->friends($user, $parts)
//            ->search($search)
            ->limit($limit ?? 50)
            ->offset($offset ?? 0)
            ->joinWith([
//            'like',
//            'alreadyViewed',
//            'postable',
                'author.profile',
//                'usersLikes',
//                'parent',
                'attachments'
            ]);
    }

    public static function find()
    {
        return new PostsQuery(static::class);
    }
}
