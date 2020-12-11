<?php


namespace app\components\response;


use app\models\Users;

class SimpleUser
{
    /**
     * @param Users $user
     * @return array
     */
    public function toArray($user)
    {
        return [
            'id' => $user->id,
//            'isOnline' => $user->isOnline,
            'lastActivity' => $user->last_activity_dt,
            'profile' => (new Profile())($user->profile),
        ];
    }

    public function __invoke($user)
    {
        return $this->toArray($user);
    }
}
