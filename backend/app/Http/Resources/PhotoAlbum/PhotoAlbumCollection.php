<?php

namespace App\Http\Resources\PhotoAlbum;

use App\Http\Resources\User\Image;
use App\Http\Resources\User\SimpleUser;
use App\Models\Community as CommunityModel;
use \App\Http\Resources\Community\Community;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PhotoAlbumCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'list' => $this->collection->map(function ($photoAlbum) {
                if ($photoAlbum->creatable instanceof CommunityModel) {
                    return [
                        'id' => $photoAlbum->id,
                        'title' => $photoAlbum->title,
                        'description' => $photoAlbum->description,
                        'community' => new Community($photoAlbum->creatable),
                        'author' => new SimpleUser($photoAlbum->author),
                        'createdAt' => $photoAlbum->created_at,
                    ];
                } else {
                    $lastImage = $photoAlbum->images()->latest()->first();

                    return [
                        'id' => $photoAlbum->id,
                        'title' => $photoAlbum->title,
                        'description' => $photoAlbum->description,
                        'user' => new SimpleUser($photoAlbum->creatable),
                        'author' => new SimpleUser($photoAlbum->author),
                        'lastImage' => $lastImage ? new Image($lastImage) : null,
                        'createdAt' => $photoAlbum->created_at,
                    ];
                }
            })
        ];
    }
}
