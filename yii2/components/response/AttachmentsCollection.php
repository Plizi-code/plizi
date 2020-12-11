<?php


namespace app\components\response;


use app\models\PostAttachments;
use yii\helpers\ArrayHelper;

class AttachmentsCollection
{
    public function toArray($attachments)
    {
        return ArrayHelper::map($attachments, 'id', static function (PostAttachments $attachment) {
            if ($attachment->mime_type === 'image/jpg' || $attachment->mime_type === 'image/jpeg' || $attachment->mime_type === 'image/gif' || $attachment->mime_type === 'image/png') {
                return [
                    'id' => $attachment->id,
                    'originalName' => $attachment->original_name,
//                    'url' => $attachment->url,
                    'mimeType' => $attachment->mime_type,
                    'size' => $attachment->size,
                    'image' => [
                        'original' => [
                            'width' => $attachment->image_original_width,
                            'height' => $attachment->image_original_height,
//                            'path' => Storage::disk('s3')->url($attachment->path)
                        ],
                        'normal' => [
                            'width' => $attachment->image_normal_width,
                            'height' => $attachment->image_normal_height,
//                            'path' => Storage::disk('s3')->url($attachment->image_normal_path)
                        ],
                        'medium' => [
                            'width' => $attachment->image_medium_width,
                            'height' => $attachment->image_medium_height,
//                            'path' => Storage::disk('s3')->url($attachment->image_medium_path)
                        ],
                        'thumb' => [
                            'width' => $attachment->image_thumb_width,
                            'height' => $attachment->image_thumb_height,
//                            'path' => Storage::disk('s3')->url($attachment->image_thumb_path)
                        ]
                    ],
                    'likes' => $attachment->likes,
//                    'alreadyLiked' => (bool)count($attachment->like),
//                    'usersLikes' => (new SimpleUsers)($attachment->usersLikes),
//                    'commentsCount' => $attachment->comments_count,
                ];
            }

            return [
                'id' => $attachment->id,
                'originalName' => $attachment->original_name,
//                'url' => $attachment->url,
                'mimeType' => $attachment->mime_type,
                'size' => $attachment->size,
            ];
        });
    }

    public function __invoke($attachments)
    {
        return $this->toArray($attachments);
    }
}
