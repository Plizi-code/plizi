<?php

namespace App\Http\Resources\Video;

use App\Http\Resources\Post\Post;
use App\Http\Resources\User\SimpleUser;
use App\Models\Post as PostModel;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VideoCollection extends ResourceCollection
{
    /**
     * @var bool
     */
    private $onlyVideoData;

    /**
     * @var int
     */
    private $totalCount;

    /**
     * VideoCollection constructor.
     * @param $resource
     * @param bool $onlyVideoData
     * @param int $totalCount
     */
    public function __construct($resource, $onlyVideoData = false, $totalCount = 0)
    {
        $this->onlyVideoData = $onlyVideoData;
        $this->totalCount = $totalCount;
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
        $onlyVideoData = $this->onlyVideoData;
        return [
            'totalCount' => $this->totalCount,
            'list' => $this->collection->map(static function ($video) use ($onlyVideoData) {
                if ($onlyVideoData) {
                    return [
                        'id' => $video->id,
                        'link' => $video->link,
                        'createdAt' => $video->created_at,
                    ];
                }

                if($video->creatableby instanceof PostModel) {
                    return [
                        'id' => $video->id,
                        'link' => $video->link,
                        'user' => new SimpleUser($video->user),
                        'post' => new Post($video->creatableby),
                        'createdAt' => $video->created_at,
                    ];
                }

                return [
                    'id' => $video->id,
                    'link' => $video->link,
                    'user' => new SimpleUser($video->user),
                    'createdAt' => $video->created_at,
                ];
            }),
        ];
    }
}
