<?php


namespace App\Services;


use App\Events\CommunityCreated;
use App\Events\CommunitySubscribe;
use App\Http\Requests\Request;
use App\Models\Community;
use App\Models\CommunityRequest;
use App\Notifications\UserSystemNotifications;
use Illuminate\Database\Query\Builder;
use Storage;

class CommunityService
{
    public function __construct()
    {
    }

    /**
     * @param $request
     * @return mixed
     */
    public function createCommunity($request) {
        $data = [
            'name' => $request->name,
            'url' => $request->url,
            'type' => $request->type,
            'theme_id' => $request->theme_id,
            'privacy' => $request->privacy,
            'is_verified' => false,
            'created_at' => time(),
            'updated_at' => time(),
        ];
        $community = Community::create($data);
        $community->users()->attach(auth()->user()->id, ['role' => Community::ROLE_AUTHOR, 'created_at' => time(), 'updated_at' => time()]);
        event(new CommunityCreated($community));
        return $community;
    }

    /**
     * @param $request
     * @param $community
     * @return mixed
     */
    public function updateCommunity(Request $request, $community) {
        $data = array_filter([
            'name' => $request->name,
            'description' => $request->description,
            'notice' => $request->notice,
            'url' => $request->url,
            'privacy' => $request->privacy,
            'type' => $request->type,
            'theme_id' => $request->themeId,
            'geo_city_id' => $request->location,
            'is_verified' => false,
            'updated_at' => time(),
        ]);

        /**
         * allow save empty string
         */
        foreach (['website', 'description', 'notice', 'url'] as $attribute) {
            if ($request->exists($attribute)) {
                $data[$attribute] = $request->$attribute;
            }
        }

        return tap($community)->update($data);
    }

    public function requestList()
    {
        /** @var Community $community */
        $community = \request()->community;

        return $community
            ? $community
                ->requests()
                ->with('user.profile.avatar', 'user.profile.city')
                ->where([
                    'status' => CommunityRequest::STATUS_NEW,
                ])
                ->limit(\request()->query('limit', 20))
                ->offset(\request()->query('offset', 0))
                ->get()
            : [];
    }

    /**
     * @return array|bool
     */
    private function getCommunityAndRequestModels()
    {
        /** @var Community $community */
        $community = \request()->community;
        if (!$community) {
            return false;
        }
        if (!$request = $community->requests->find(\request()->id)) {
            return false;
        }
        if ($request->status !== CommunityRequest::STATUS_NEW) {
            return false;
        }

        return [$community, $request];
    }

    /**
     * @param $community
     * @return array
     */
    private function getCommunityPayload($community)
    {
        return [
            'name' => $community->name,
            'primaryImage' => $community->avatar
                ? Storage::disk('s3')->url($community->avatar->image_thumb_path)
                : $community->primary_image,
            'id' => $community->id,
        ];
    }

    /**
     * @return bool
     */
    public function requestAccept()
    {
        if (!$models = $this->getCommunityAndRequestModels()) {
            return false;
        }

        /** @var Community|Builder $community */
        [$community, $request] = $models;
        if (!tap($request)->update([
                'status' => CommunityRequest::STATUS_ACCEPTED,
            ])) {
            return false;
        }

        if(!$community->users()->where([
            'id' => $request->user_id,
        ])->exists()) {
            $community->users()->attach($request->user_id,
                ['role' => Community::ROLE_USER, 'created_at' => time(), 'updated_at' => time()]);
            event(new CommunitySubscribe($community->id, $request->user_id));

            $request->user->notify(new UserSystemNotifications([
                'community' => $this->getCommunityPayload($community),
                'body' => 'Your request accepted',
                'notificationType' => 'community.request.accepted',
            ]));
        }

        return true;
    }

    /**
     * @return bool
     */
    public function requestReject()
    {
        if (!$models = $this->getCommunityAndRequestModels()) {
            return false;
        }

        [$community, $request] = $models;
        if (!tap($request)->update([
            'status' => CommunityRequest::STATUS_REJECTED,
        ])) {
            return false;
        }

        $request->user->notify(new UserSystemNotifications([
            'community' => $this->getCommunityPayload($community),
            'body' => 'Your request rejected',
            'notificationType' => 'community.request.rejected',
        ]));

        return true;
    }

}
