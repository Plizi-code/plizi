<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comments".
 *
 * @property int $id
 * @property string $commentable_type
 * @property int $commentable_id
 * @property string $author_id
 * @property string $body
 * @property int|null $reply_on
 * @property int $updated_at
 * @property int $created_at
 * @property int $likes
 */
class Comments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['commentable_type', 'commentable_id', 'author_id', 'body', 'updated_at', 'created_at'], 'required'],
            [['commentable_id', 'reply_on', 'updated_at', 'created_at', 'likes'], 'integer'],
            [['commentable_type', 'body'], 'string', 'max' => 191],
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
            'commentable_type' => 'Commentable Type',
            'commentable_id' => 'Commentable ID',
            'author_id' => 'Author ID',
            'body' => 'Body',
            'reply_on' => 'Reply On',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'likes' => 'Likes',
        ];
    }
}
