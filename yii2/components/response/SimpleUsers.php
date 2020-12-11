<?php


namespace app\components\response;


use app\models\Users;
use yii\helpers\ArrayHelper;

class SimpleUsers
{
    public function toArray($users)
    {
        return ArrayHelper::map($users, 'id', static function (Users $user) {
            return [
                'id' => $user->id,
                'email' => $user->email,
//                'isOnline' => $user->isOnline,
                'lastActivity' => $user->last_activity_dt,
                'profile' => (new Profile)($user->profile),
            ];
        });
    }

    public function __invoke($users)
    {
        return $this->toArray($users);
    }
}
