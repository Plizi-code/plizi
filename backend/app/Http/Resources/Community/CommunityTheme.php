<?php

namespace App\Http\Resources\Community;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommunityTheme extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'children' => $this->children
                ? new CommunityThemesCollection($this->children)
                : null,
        ];
    }
}
