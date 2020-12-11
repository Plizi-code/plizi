<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "geo_cities".
 *
 * @property int $id
 * @property int $country_id
 * @property int $region_id
 * @property string $important
 * @property string $title_ru
 * @property string $area_ru
 * @property string $region_ru
 * @property string $title_ua
 * @property string $area_ua
 * @property string $region_ua
 * @property string $title_en
 * @property string $area_en
 * @property string $region_en
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Communities[] $communities
 * @property Profiles[] $profiles
 */
class GeoCities extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'geo_cities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'country_id', 'region_id', 'important', 'title_ru', 'area_ru', 'region_ru', 'title_ua', 'area_ua', 'region_ua', 'title_en', 'area_en', 'region_en'], 'required'],
            [['id', 'country_id', 'region_id', 'created_at', 'updated_at'], 'integer'],
            [['important'], 'string'],
            [['title_ru', 'area_ru', 'region_ru', 'title_ua', 'area_ua', 'region_ua', 'title_en', 'area_en', 'region_en'], 'string', 'max' => 150],
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
            'country_id' => 'Country ID',
            'region_id' => 'Region ID',
            'important' => 'Important',
            'title_ru' => 'Title Ru',
            'area_ru' => 'Area Ru',
            'region_ru' => 'Region Ru',
            'title_ua' => 'Title Ua',
            'area_ua' => 'Area Ua',
            'region_ua' => 'Region Ua',
            'title_en' => 'Title En',
            'area_en' => 'Area En',
            'region_en' => 'Region En',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Communities]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCommunities()
    {
        return $this->hasMany(Communities::class, ['geo_city_id' => 'id']);
    }

    /**
     * Gets query for [[Profiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(Profiles::class, ['geo_city_id' => 'id']);
    }
}
