<?php

namespace App\Http\Resources\Photo;

use App\Http\Resources\User\SimpleUsers;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class PhotoCollection extends ResourceCollection
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
            'list' => $this->collection->map(function ($attachment) {
                return [
                        'id' => $attachment->id,
                        'originalName' => $attachment->original_name,
                        'url' => $attachment->url,
                        'mimeType' => $attachment->mime_type,
                        'size' => $attachment->size,
                        'image' => [
                            'original' => [
                                'width' => $attachment->image_original_width,
                                'height' => $attachment->image_original_height,
                                'path' => Storage::disk('s3')->url($attachment->path)
                            ],
                            'normal' => [
                                'width' => $attachment->image_normal_width,
                                'height' => $attachment->image_normal_height,
                                'path' => Storage::disk('s3')->url($attachment->image_normal_path)
                            ],
                            'medium' => [
                                'width' => $attachment->image_medium_width,
                                'height' => $attachment->image_medium_height,
                                'path' => Storage::disk('s3')->url($attachment->image_medium_path)
                            ],
                            'thumb' => [
                                'width' => $attachment->image_thumb_width,
                                'height' => $attachment->image_thumb_height,
                                'path' => Storage::disk('s3')->url($attachment->image_thumb_path)
                            ]
                        ],
//                        'likes' => $attachment->likes,
//                        'alreadyLiked' => (bool)count($attachment->like),
//                        'usersLikes' => new SimpleUsers($attachment->usersLikes),
                    ];
            })
        ];
    }
}
