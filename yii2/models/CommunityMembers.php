<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "community_members".
 *
 * @property int $community_id
 * @property string $user_id
 * @property string|null $role
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int $subscribed
 *
 * @property Communities $community
 * @property Users $user
 */
class CommunityMembers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'community_members';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['community_id', 'user_id'], 'required'],
            [['community_id', 'created_at', 'updated_at', 'subscribed'], 'integer'],
            [['role'], 'string'],
            [['user_id'], 'string', 'max' => 24],
            [['community_id'], 'exist', 'skipOnError' => true, 'targetClass' => Communities::class, 'targetAttribute' => ['community_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'community_id' => 'Community ID',
            'user_id' => 'User ID',
            'role' => 'Role',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'subscribed' => 'Subscribed',
        ];
    }

    /**
     * Gets query for [[Community]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCommunity()
    {
        return $this->hasOne(Communities::class, ['id' => 'community_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }
}
