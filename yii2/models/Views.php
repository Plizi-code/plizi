<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "views".
 *
 * @property string $user_id
 * @property int $viewable_id
 * @property string|null $viewable_type
 * @property int $updated_at
 * @property int $created_at
 *
 * @property Users $user
 */
class Views extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'views';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'viewable_id'], 'required'],
            [['viewable_id', 'updated_at', 'created_at'], 'integer'],
            [['user_id'], 'string', 'max' => 24],
            [['viewable_type'], 'string', 'max' => 191],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'viewable_id' => 'Viewable ID',
            'viewable_type' => 'Viewable Type',
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
