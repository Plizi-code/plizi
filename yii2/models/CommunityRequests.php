<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "community_requests".
 *
 * @property int $id
 * @property string $user_id
 * @property int $community_id
 * @property string $description
 * @property int $status
 * @property int $updated_at
 * @property int $created_at
 *
 * @property Communities $community
 * @property Users $user
 */
class CommunityRequests extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'community_requests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'community_id', 'description', 'status', 'updated_at', 'created_at'], 'required'],
            [['community_id', 'status', 'updated_at', 'created_at'], 'integer'],
            [['description'], 'string'],
            [['user_id'], 'string', 'max' => 24],
            [['community_id'], 'exist', 'skipOnError' => true, 'targetClass' => Communities::className(), 'targetAttribute' => ['community_id' => 'id']],
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
            'community_id' => 'Community ID',
            'description' => 'Description',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Community]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCommunity()
    {
        return $this->hasOne(Communities::className(), ['id' => 'community_id']);
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
