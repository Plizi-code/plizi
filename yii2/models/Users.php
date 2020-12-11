<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property string $id
 * @property string|null $email
 * @property string|null $token
 * @property string|null $email_verified_at
 * @property string|null $password
 * @property string|null $remember_token
 * @property string|null $username
 * @property int $is_admin
 * @property int $created_at
 * @property int $updated_at
 * @property int|null $last_activity_dt
 *
// * @property CommunityAttachments[] $communityAttachments
// * @property CommunityHeaders[] $communityHeaders
 * @property CommunityMembers[] $communityMembers
// * @property CommunityRequests[] $communityRequests
// * @property ImageUploads[] $imageUploads
 * @property Likes[] $likes
// * @property LinkedSocialAccounts[] $linkedSocialAccounts
// * @property PhotoAlbums[] $photoAlbums
 * @property PostAttachments[] $postAttachments
 * @property Profiles $profile
// * @property Videos[] $videos
 * @property Views[] $views
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'required'],
            [['email_verified_at'], 'safe'],
            [['is_admin', 'created_at', 'updated_at', 'last_activity_dt'], 'integer'],
            [['id'], 'string', 'max' => 24],
            [['email', 'token', 'password', 'username'], 'string', 'max' => 191],
            [['remember_token'], 'string', 'max' => 100],
            [['email'], 'unique'],
            [['token'], 'unique'],
            [['username'], 'unique'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'token' => 'Token',
            'email_verified_at' => 'Email Verified At',
            'password' => 'Password',
            'remember_token' => 'Remember Token',
            'username' => 'Username',
            'is_admin' => 'Is Admin',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'last_activity_dt' => 'Last Activity Dt',
        ];
    }

    /**
     * Gets query for [[CommunityAttachments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCommunityAttachments()
    {
        return $this->hasMany(CommunityAttachments::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[CommunityHeaders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCommunityHeaders()
    {
        return $this->hasMany(CommunityHeaders::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[CommunityMembers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCommunityMembers()
    {
        return $this->hasMany(CommunityMembers::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[CommunityRequests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCommunityRequests()
    {
        return $this->hasMany(CommunityRequests::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[ImageUploads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImageUploads()
    {
        return $this->hasMany(ImageUploads::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Likes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLikes()
    {
        return $this->hasMany(Likes::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[LinkedSocialAccounts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinkedSocialAccounts()
    {
        return $this->hasMany(LinkedSocialAccounts::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[PhotoAlbums]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPhotoAlbums()
    {
        return $this->hasMany(PhotoAlbums::class, ['author_id' => 'id']);
    }

    /**
     * Gets query for [[PostAttachments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPostAttachments()
    {
        return $this->hasMany(PostAttachments::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Profiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profiles::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Videos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVideos()
    {
        return $this->hasMany(Videos::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Views]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getViews()
    {
        return $this->hasMany(Views::class, ['user_id' => 'id']);
    }

    public function getCommunities()
    {
//        return $this->hasMany(Communities::class, )
    }
}
