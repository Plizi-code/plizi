<?php

namespace App\Http\Resources\Community;

use App\Http\Resources\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommunityRequests extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'list' => $this->collection->map(static function($row) {
                return [
                    'id' => $row->id,
//                    'community' => new Community($request->community),
                    'created_at' => $row->created_at,
                    'description' => $row->description,
                    'user' => new User($row->user),
                ];
            }),
        ];
    }
}
