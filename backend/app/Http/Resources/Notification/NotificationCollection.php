<?php

namespace App\Http\Resources\Notification;

use App\Http\Resources\Community\Community;
use App\Http\Resources\User\User;
use App\Models\Community as CommunityModel;
use App\Models\User as UserModel;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationCollection extends ResourceCollection
{

    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'list' => $this->collection->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'data' => $notification->data['data'],
                    'readAt' => $notification->read_at,
                    'createdAt' => $notification->created_at
                ];
            }),
            'total' => \Auth::user()->notificationsCount
        ];
    }
}
