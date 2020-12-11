<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\Query;

/**
 * This is the ActiveQuery class for [[Posts]].
 *
 * @see Posts
 */
class PostsQuery extends ActiveQuery
{
    public $queryCacheDuration = 120;

    /**
     * {@inheritdoc}
     * @return Posts[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Posts|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param Users $user
     * @param $parts
     */
    public function communities($user, $parts)
    {
        if ($parts && !in_array('communities', $parts, true)) {
            return $this;
        }
        $subQuery = $user->getCommunityMembers()
            ->select('community_id');

        return $this->orWhere([
            'postable_type' => "App\Models\Community",
            'postable_id' => $subQuery,
        ]);
    }

    /**
     * @param Users $user
     * @param $parts
     */
    public function own($user, $parts)
    {
        if ($parts && !in_array('own', $parts, true)) {
            return $this;
        }
        return $this->orWhere([
            'postable_type' => "App\Models\User",
            'postable_id' => $user->id,
        ]);
    }

    /**
     * @param Users $user
     * @param $parts
     */
    public function friends($user, $parts)
    {
        if ($parts && !in_array('friends', $parts, true)) {
            return $this;
        }
        $subQuery = (new Query())
            ->select(new Expression("IF(sender_id='{$user->id}',recipient_id,sender_id) AS id"))
            ->from('friendships')
            ->where(['status' => 1])
            ->andWhere("(sender_id='{$user->id}' OR recipient_id='{$user->id}')");

        return $this->orWhere([
            'postable_type' => "App\Models\User",
            'postable_id' => $subQuery,
        ]);
    }
}
