<?php


namespace App\Http\Resources\User;

use Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class Image extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'originalName' => $this->original_name,
            'url' => $this->url,
            'path' => $this->path,
            'mimeType' => $this->mime_type,
            'size' => $this->size,
            'image' => [
                'original' => [
                    'width' => $this->image_original_width,
                    'height' => $this->image_original_height,
                    'path' => Storage::disk('s3')->url($this->path)
                ],
                'normal' => [
                    'width' => $this->image_normal_width,
                    'height' => $this->image_normal_height,
                    'path' => Storage::disk('s3')->url($this->image_normal_path)
                ],
                'medium' => [
                    'width' => $this->image_medium_width,
                    'height' => $this->image_medium_height,
                    'path' => Storage::disk('s3')->url($this->image_medium_path)
                ],
                'thumb' => [
                    'width' => $this->image_thumb_width,
                    'height' => $this->image_thumb_height,
                    'path' => Storage::disk('s3')->url($this->image_thumb_path)
                ]
            ]
        ];
    }
}
