<?php

namespace App\Http\Resources\Notification;

use Illuminate\Http\Resources\Json\JsonResource;

class Notification extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'data' => $this->data['data'],
            'readAt' => $this->read_at,
            'createdAt' => $this->created_at
        ];
    }
}
