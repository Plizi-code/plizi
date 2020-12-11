<?php

namespace App\Http\Resources\Community;

use App\Http\Resources\Geo\City as CityResource;
use App\Http\Resources\User\Image;
use App\Models\CommunityRequest;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class Community extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'notice' => $this->notice,
            'primaryImage' => $this->primary_image,
            'url' => $this->url,
            'website' => $this->website,
            'privacy' => $this->privacy,
            'type' => $this->type,
            'themeId' => $this->theme_id,
            'location' => $this->city ? new CityResource($this->city) : null,
            'totalMembers' => $this->members_count,
            'totalVideos' => Video::specialForCommunity($this->id)->count(),
            'role' => $this->role ? $this->role->role : null,
            'avatar' => $this->avatar ? new Image($this->avatar) : null,
            'headerImage' => $this->headerImage ? new Image($this->headerImage) : null,
            'friends' => $this->getFriends($request),
            'subscribed' => $this->role ? (bool)$this->role->subscribed : false,
            'admins' => $this->supers ? new CommunityUserCollection($this->supers, $this->role ?: null) : [],
        ];
        if ($this && $this->relationLoaded('users')) {
            $data['members'] = new CommunityUserCollection($this->users);
        }

        $data['requestsCount'] = in_array($data['role'], ['author', 'admin'])
            ? CommunityRequest::where('community_id', $this->id)
                ->where('status', CommunityRequest::STATUS_NEW)
                ->count()
            : 0;

        return $data;
    }

    private function getFriends($request)
    {
        if (!$friends = $this->friends()) {
            return null;
        }
        $collection = collect($friends);
        $users = User::whereIn('id', $collection->pluck('oid'))
            ->with('profile')
            ->get();
        return [
            'total' => Arr::get($collection->first(), 'total_count'),
            'list' => Arr::get((new CommunityUserCollection($users))->toArray($request), 'list'),
        ];
    }
}
