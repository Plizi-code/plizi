<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "likes".
 *
 * @property int $id
 * @property string $user_id
 * @property int $likeable_id
 * @property string|null $likeable_type
 * @property int $updated_at
 * @property int $created_at
 *
 * @property Users $user
 */
class Likes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'likes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'likeable_id'], 'required'],
            [['likeable_id', 'updated_at', 'created_at'], 'integer'],
            [['user_id'], 'string', 'max' => 24],
            [['likeable_type'], 'string', 'max' => 191],
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
            'likeable_id' => 'Likeable ID',
            'likeable_type' => 'Likeable Type',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
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
