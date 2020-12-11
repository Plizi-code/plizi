<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "profiles".
 *
 * @property string $user_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string $sex
 * @property string|null $birthday
 * @property int|null $relationship_id
 * @property string $user_pic
 * @property int $created_at
 * @property int $updated_at
 * @property int|null $geo_city_id
 * @property string|null $relationship_user_id
 * @property int $follower_count
 * @property int $video_count
 * @property int $image_count
 *
 * @property GeoCities $city
 * @property Users $user
 * @property Users $relationshipUser
 */
class Profiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profiles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at', 'updated_at'], 'required'],
            [['sex'], 'string'],
            [['birthday'], 'safe'],
            [['relationship_id', 'created_at', 'updated_at', 'geo_city_id', 'follower_count', 'video_count', 'image_count'], 'integer'],
            [['user_id', 'relationship_user_id'], 'string', 'max' => 24],
            [['first_name', 'last_name'], 'string', 'max' => 200],
            [['user_pic'], 'string', 'max' => 191],
            [['geo_city_id'], 'exist', 'skipOnError' => true, 'targetClass' => GeoCities::class, 'targetAttribute' => ['geo_city_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'sex' => 'Sex',
            'birthday' => 'Birthday',
            'relationship_id' => 'Relationship ID',
            'user_pic' => 'User Pic',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'geo_city_id' => 'Geo City ID',
            'relationship_user_id' => 'Relationship User ID',
            'follower_count' => 'Follower Count',
            'video_count' => 'Video Count',
            'image_count' => 'Image Count',
        ];
    }

    /**
     * Gets query for [[GeoCity]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(GeoCities::class, ['id' => 'geo_city_id']);
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

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRelationshipUser()
    {
        return $this->hasOne(Users::class, ['id' => 'relationship_user_id']);
    }

}
