<?php

namespace app\components\traits;

use app\models\Communities;
use app\models\Users;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

trait Morphed
{

    public function morphTo($morphed)
    {
        $class = $this->getMorphToClass($morphed);
        if ($class && class_exists($class)) {
            /** @var ActiveRecord $this */
            $query = $this->hasOne($class, ['id' => $morphed . '_id']);
            if ($class === Users::class) {
                $query->joinWith(['profile.city']);
            }
            if ($class === Communities::class) {
                $query->joinWith(['communityMembers']);
            }
            return $query;
        }
        return false;
    }

    private function getMorphToClass($morphed)
    {
        $class = $this->{$morphed . '_type'};
        [, , $class] = explode('\\', $class);
        return ArrayHelper::getValue([
            'User' => Users::class,
            'Community' => Communities::class,
        ], $class);
    }

}
