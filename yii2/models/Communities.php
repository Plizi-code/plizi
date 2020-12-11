<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "communities".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $notice
 * @property string|null $primary_image
 * @property string|null $url
 * @property string|null $website
 * @property int $is_verified
 * @property int $created_at
 * @property int $updated_at
 * @property int $type
 * @property int $theme_id
 * @property int $privacy
 * @property int|null $geo_city_id
 * @property int $video_count
 *
 * @property GeoCities $city
 * @property CommunityAttachments[] $communityAttachments
 * @property CommunityHeaders[] $communityHeaders
 * @property CommunityMembers[] $communityMembers
 * @property CommunityRequests[] $communityRequests
 */
class Communities extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'communities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type', 'theme_id', 'privacy'], 'required'],
            [['description'], 'string'],
            [['is_verified', 'created_at', 'updated_at', 'type', 'theme_id', 'privacy', 'geo_city_id', 'video_count'], 'integer'],
            [['name', 'notice', 'primary_image', 'url', 'website'], 'string', 'max' => 191],
            [['geo_city_id'], 'exist', 'skipOnError' => true, 'targetClass' => GeoCities::className(), 'targetAttribute' => ['geo_city_id' => 'id']],
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
            'description' => 'Description',
            'notice' => 'Notice',
            'primary_image' => 'Primary Image',
            'url' => 'Url',
            'website' => 'Website',
            'is_verified' => 'Is Verified',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'type' => 'Type',
            'theme_id' => 'Theme ID',
            'privacy' => 'Privacy',
            'geo_city_id' => 'Geo City ID',
            'video_count' => 'Video Count',
        ];
    }

    /**
     * Gets query for [[GeoCity]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(GeoCities::className(), ['id' => 'geo_city_id']);
    }

    /**
     * Gets query for [[CommunityAttachments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCommunityAttachments()
    {
        return $this->hasMany(CommunityAttachments::className(), ['community_id' => 'id']);
    }

    /**
     * Gets query for [[CommunityHeaders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCommunityHeaders()
    {
        return $this->hasMany(CommunityHeaders::className(), ['community_id' => 'id']);
    }

    /**
     * Gets query for [[CommunityMembers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCommunityMembers()
    {
        return $this->hasMany(CommunityMembers::className(), ['community_id' => 'id']);
    }

    /**
     * Gets query for [[CommunityRequests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCommunityRequests()
    {
        return $this->hasMany(CommunityRequests::className(), ['community_id' => 'id']);
    }
}
