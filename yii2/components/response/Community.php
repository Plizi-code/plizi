<?php


namespace app\components\response;


use app\models\Communities;

class Community
{
    private static $totalMembers = [];

    /**
     * @param Communities $community
     * @return int
     */
    private function getTotalMembers($community)
    {
        if (!array_key_exists($community->id, self::$totalMembers)) {
            self::$totalMembers[$community->id] = $community->getCommunityMembers()->count();
        }
        return self::$totalMembers[$community->id];
    }

    public function toArray(Communities $community)
    {
        $data = [
            'id' => $community->id,
            'name' => $community->name,
            'description' => $community->description,
            'notice' => $community->notice,
            'primaryImage' => $community->primary_image,
            'url' => $community->url,
            'website' => $community->website,
            'privacy' => $community->privacy,
            'type' => $community->type,
            'themeId' => $community->theme_id,
            'location' => $community->city ? (new City)($community->city) : null,
            'totalMembers' => $this->getTotalMembers($community),
//            'totalVideos' => Video::specialForCommunity($community->id)->count(),
//            'role' => $community->role ? $community->role->role : null,
//            'avatar' => $community->avatar ? new Image($community->avatar) : null,
//            'headerImage' => $community->headerImage ? new Image($community->headerImage) : null,
//            'friends' => $community->getFriends($request),
//            'subscribed' => $community->role ? (bool)$community->role->subscribed : false,
//            'admins' => $community->supers ? new CommunityUserCollection($community->supers, $community->role ?: null) : [],
        ];
//        if ($this && $community->relationLoaded('users')) {
//            $data['members'] = new CommunityUserCollection($community->users);
//        }

//        $data['requestsCount'] = in_array($data['role'], ['author', 'admin'])
//            ? CommunityRequests::find()
//                ->where([
//                    'community_id' => $community->id,
//                    'status' => 0
//                ])
//                ->count()
//            : 0;

        return $data;
    }

//    private function getFriends($community)
//    {
//        if (!$friends = $community->friends()) {
//            return null;
//        }
//        $collection = collect($friends);
//        $users = User::whereIn('id', $collection->pluck('oid'))
//            ->with('profile')
//            ->get();
//        return [
//            'total' => Arr::get($collection->first(), 'total_count'),
//            'list' => Arr::get((new CommunityUserCollection($users))->toArray($request), 'list'),
//        ];
//    }

    public function __invoke($community)
    {
        return $this->toArray($community);
    }
}
