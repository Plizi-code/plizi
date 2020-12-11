<?php

namespace App\Http\Resources\PhotoAlbum;

use App\Http\Resources\User\ImagesCollection;
use App\Models\Community as CommunityModel;
use App\Models\User as UserModel;
use App\Http\Resources\Community\Community;
use App\Http\Resources\User\SimpleUser;
use Illuminate\Http\Resources\Json\JsonResource;

class PhotoAlbum extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'user' => $this->creatable instanceof UserModel ? new SimpleUser($this->creatable) : null,
            'community' => $this->creatable instanceof CommunityModel ? new Community($this->creatable) : null,
            'author' => new SimpleUser($this->author),
            'images' => new ImagesCollection($this->images()->orderByDesc('id')->get()),
            'createdAt' => $this->created_at,
        ];
    }
}
