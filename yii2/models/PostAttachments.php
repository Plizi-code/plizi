<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "post_attachments".
 *
 * @property int $id
 * @property string $user_id
 * @property int|null $post_id
 * @property string $original_name
 * @property string $path
 * @property string $mime_type
 * @property int $size
 * @property int $updated_at
 * @property int $created_at
 * @property string|null $image_normal_path
 * @property string|null $image_medium_path
 * @property string|null $image_thumb_path
 * @property int|null $image_normal_width
 * @property int|null $image_normal_height
 * @property int|null $image_thumb_width
 * @property int|null $image_thumb_height
 * @property int|null $image_medium_width
 * @property int|null $image_medium_height
 * @property int|null $image_original_width
 * @property int|null $image_original_height
 * @property int $likes
 *
 * @property Posts $post
 * @property Users $user
 */
class PostAttachments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post_attachments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'updated_at', 'created_at'], 'required'],
            [['post_id', 'size', 'updated_at', 'created_at', 'image_normal_width', 'image_normal_height', 'image_thumb_width', 'image_thumb_height', 'image_medium_width', 'image_medium_height', 'image_original_width', 'image_original_height', 'likes'], 'integer'],
            [['user_id'], 'string', 'max' => 24],
            [['original_name', 'path', 'mime_type', 'image_normal_path', 'image_medium_path', 'image_thumb_path'], 'string', 'max' => 191],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Posts::className(), 'targetAttribute' => ['post_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'post_id' => 'Post ID',
            'original_name' => 'Original Name',
            'path' => 'Path',
            'mime_type' => 'Mime Type',
            'size' => 'Size',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'image_normal_path' => 'Image Normal Path',
            'image_medium_path' => 'Image Medium Path',
            'image_thumb_path' => 'Image Thumb Path',
            'image_normal_width' => 'Image Normal Width',
            'image_normal_height' => 'Image Normal Height',
            'image_thumb_width' => 'Image Thumb Width',
            'image_thumb_height' => 'Image Thumb Height',
            'image_medium_width' => 'Image Medium Width',
            'image_medium_height' => 'Image Medium Height',
            'image_original_width' => 'Image Original Width',
            'image_original_height' => 'Image Original Height',
            'likes' => 'Likes',
        ];
    }

    /**
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Posts::className(), ['id' => 'post_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
