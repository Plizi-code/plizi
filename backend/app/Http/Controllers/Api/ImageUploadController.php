<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\Image;
use App\Http\Resources\User\ImagesCollection;
use App\Models\Community;
use App\Models\ImageUpload;
use App\Http\Requests\ImageUpload\StoreImage;
use App\Models\PhotoAlbum;
use App\Models\User;
use App\Services\S3UploadService;
use Exception;

class ImageUploadController extends Controller
{
    /**
     * @var ImageUpload
     */
    private $imageUpload;

    /**
     * @var S3UploadService
     */
    private $uploadService;

    /**
     * ImageUploadController constructor.
     * @param ImageUpload $imageUpload
     * @param S3UploadService $uploadService
     */
    public function __construct(ImageUpload $imageUpload, S3UploadService $uploadService)
    {
        $this->imageUpload = $imageUpload;
        $this->uploadService = $uploadService;
    }

    /**
     * @param StoreImage $request
     * @return Image
     * @throws Exception
     */
    public function upload(StoreImage $request)
    {
        $uploaded = $this->uploadService->singleUpload('images/originals', $request->image, 'public', [
            'normal' => [
                'size' => 600,
            ],
            'medium' => [
                'size' => [211, 211],
            ],
            'thumb' => [
                'size' => [80, 80],
            ],
        ]);
        $creatable = [
            'creatable_id' => \Auth::id(),
            'creatable_type' => User::class,
        ];
        $request->merge($uploaded);
        $request->merge($creatable);

        /** @var ImageUpload $image_upload */
        $image_upload = $this->imageUpload->create($request->only('original_name', 'path', 'title', 'size', 'tag', 'mime_type',
            'image_original_width',
            'image_original_height',
            'image_normal_path',
            'image_normal_width',
            'image_normal_height',
            'image_medium_path',
            'image_medium_width',
            'image_medium_height',
            'image_thumb_path',
            'image_thumb_width',
            'image_thumb_height',
            'creatable_id',
            'creatable_type'));

        $image_upload->deleteDublicatesForAvatar();

        return new Image($image_upload);
    }

    public function getUserImages(User $user)
    {
        $isFriend = $user->isFriendWith(auth()->user());

        if ($user->id !== \Auth::id()) {
            if (($user->privacySettings->page_type === 2 && !$isFriend) ||
                $user->privacySettings->page_type === 3) {
                return [
                    'data' => [
                        'list' => []
                    ]
                ];
            }
        }

        $images = $user->images()
            ->orderByDesc('id')
            ->limit(20)
            ->get();

        return new ImagesCollection($images);
    }

    public function delete(ImageUpload $imageUpload)
    {
        $user = \Auth::user();

        if ($imageUpload->creatable instanceof User) {
            if ($imageUpload->user->id === \Auth::id()) {
                try {
                    $imageUpload->delete();
                } catch (Exception $e) {
                    return response()->json([
                        'message' => 'Ошибка удаления изображения.',
                    ], 422);
                }

                $imageUpload->albums()->detach();
                \Auth::user()->profile()->decrement('image_count');

                return response()->json([
                    'message' => 'Изображение успешно удалено.',
                ]);
            }

            return response()->json([
                'message' => 'У вас нет доступа.',
            ], 403);
        }

        if ($imageUpload->creatable instanceof Community) {
            $community = $user->community()
                ->where('id', $imageUpload->creatable->id)
                ->first();

            if ($community && $community->pivot->role === 'author') {
                try {
                    $imageUpload->delete();
                } catch (Exception $e) {
                    return response()->json([
                        'message' => 'Ошибка удаления изображения.',
                    ], 422);
                }

                $imageUpload->albums()->detach();
//                $community->decrement('image_count');

                return response()->json([
                    'message' => 'Изображение успешно удалено.',
                ]);
            }

            return response()->json([
                'message' => 'У вас нет доступа.',
            ], 403);
        }

        return response()->json([
            'message' => 'Неизвестая ошибка',
        ], 422);
    }
}
