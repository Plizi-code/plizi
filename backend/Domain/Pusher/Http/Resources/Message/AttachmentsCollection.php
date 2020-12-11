<?php

namespace Domain\Pusher\Http\Resources\Message;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Storage;

class AttachmentsCollection extends ResourceCollection
{

    /**
     * AttachmentsCollection constructor.
     * @param $resource
     */
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'list' => $this->collection->map(function ($attachment) {
                if ($attachment->mime_type == 'image/jpg' || $attachment->mime_type == 'image/jpeg' || $attachment->mime_type == 'image/gif' || $attachment->mime_type == 'image/png') {
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
                        ]
                    ];
                } else {
                    return [
                        'id' => $attachment->id,
                        'originalName' => $attachment->original_name,
                        'url' => $attachment->url,
                        'mimeType' => $attachment->mime_type,
                        'size' => $attachment->size,
                    ];
                }

            })
        ];
    }
}
