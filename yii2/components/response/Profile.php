<?php


namespace app\components\response;


use app\models\Profiles;

class Profile
{
    /**
     * @param Profiles $profile
     * @return array
     */
    public function toArray($profile)
    {
        return [
            'firstName' => $profile->first_name,
            'lastName' => $profile->last_name,
            'sex' => $profile->sex,
            'birthday' => $profile->birthday,
            'location' => $profile->city ? (new City)($profile->city) : null,
            'relationshipId' => $profile->relationship_id,
            'relationshipUser' => $profile->relationship_user_id ? (new SimpleUser())($profile->relationshipUser) : null,
            'userPic' => $profile->user_pic,
//            'avatar' => $profile->avatar
//                ? new Image($this->avatar)
//                : null
        ];
    }

    public function __invoke($profile)
    {
        return $this->toArray($profile);
    }
}
