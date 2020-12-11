<?php

namespace App\Http\Controllers\Api;

use \App\Http\Resources\PhotoAlbum\PhotoAlbum as PhotoAlbumJsonResource;
use App\Http\Requests\PhotoAlbum\PhotoAlbumStore;
use App\Http\Requests\PhotoAlbum\PhotoAlbumUpdate;
use App\Http\Requests\Post\UploadFileRequest;
use App\Http\Resources\PhotoAlbum\PhotoAlbumCollection;
use App\Http\Resources\Post\AttachmentsCollection;
use App\Models\Community;
use App\Models\ImageUpload;
use App\Models\PhotoAlbum;
use App\Models\User;
use App\Services\S3UploadService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PhotoAlbumController extends Controller
{
    /**
     * @var S3UploadService
     */
    private $uploadService;

    /**
     * ImageUploadController constructor.
     * @param S3UploadService $uploadService
     */
    public function __construct(S3UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return PhotoAlbumCollection
     */
    public function index()
    {
        $photo_albums = \Auth::user()
            ->photoAlbums()
            ->with(['creatable', 'author'])
            ->orderByDesc('id')
            ->get();

        return new PhotoAlbumCollection($photo_albums);
    }

    public function indexByCommunity(Request $request, $community)
    {
        $photo_albums = Community::find($community)
            ->photoAlbums()
            ->with(['creatable', 'author'])
            ->orderByDesc('id')
            ->get();

        return new PhotoAlbumCollection($photo_albums);
    }

    public function getByUserId(User $user)
    {
        $photo_albums = $user->photoAlbums()
            ->with(['creatable', 'author'])
            ->orderByDesc('id')
            ->get();

        return new PhotoAlbumCollection($photo_albums);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PhotoAlbumStore $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PhotoAlbumStore $request)
    {
        // TODO: @YZ Добавить проверку на сообщество.

        $photo_album = PhotoAlbum::create([
            'author_id' => \Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'creatable_id' => $request->communityId ? $request->communityId : \Auth::id(),
            'creatable_type' => $request->communityId ? Community::class : User::class,
        ]);

        return response()->json([
            'data' => [
                'id' => $photo_album->id,
            ],
        ]);
    }

    public function storePhotoInAlbum(UploadFileRequest $request, $id)
    {
        if (!$photoAlbum = auth()->user()->photoAlbums()->find($id)) {
            return response()->json([
                'message' => 'У вас нет доступа'
            ], 403);
        }
        $additionalData = [
           'creatable_id' => \Auth::id(),
           'creatable_type' => User::class,
        ];
        $photo_ids = $this->uploadService->uploadFiles(new ImageUpload(), 'photoAlbum', $request->allFiles(), 'public', [], $additionalData);
        $photos = ImageUpload::whereIn('id', $photo_ids)->get();
        $photoAlbum->images()->attach($photo_ids);
        \Auth::user()->profile()->increment('image_count');

        return new AttachmentsCollection($photos);
    }

    public function show(PhotoAlbum $photoAlbum)
    {
        $author = $photoAlbum->author;

        if (\Auth::id() !== $author->id) {
            $isFriend = $author->isFriendWith(auth()->user());

            if (($author->privacySettings->page_type === 2 && !$isFriend) ||
                $author->privacySettings->page_type === 3) {
                return [
                    'data' => [
                        'list' => []
                    ]
                ];
            }
        }

        $photo_album = $author->photoAlbums()
            ->where('id', $photoAlbum->id)
            ->first();

        return new PhotoAlbumJsonResource($photo_album);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PhotoAlbumUpdate $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PhotoAlbumUpdate $request, $id)
    {
        $photoAlbum = PhotoAlbum::find($id);
        $user = \Auth::user();

        if ($photoAlbum->creatable instanceof Community) {
            $community = $user->communities()
                ->where('id', $photoAlbum->creatable->id)
                ->first();

            if ($community && $community->pivot->role === 'author') {
                $photoAlbum->update([
                    'title' => $request->title,
                    'description' => $request->description,
                ]);
            }
        } else if ($photoAlbum->creatable instanceof User) {
            if (($photoAlbum->creatable->id === $user->id) || ($photoAlbum->author_id === $user->id)) {
                $photoAlbum->update([
                    'title' => $request->title,
                    'description' => $request->description,
                ]);
            }
        }

        return response()->json([
            'message' => 'Фотоальбом успешно обновлен.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $photoAlbum = PhotoAlbum::find($id);
        $user = \Auth::user();

        if ($photoAlbum->creatable instanceof Community) {
            $community = $user->communities()
                ->where('id', $photoAlbum->creatable->id)
                ->first();

            if ($community && $community->pivot->role === 'author') {
                $photoAlbum->delete();
            }
        } else if ($photoAlbum->creatable instanceof User) {
            if (($photoAlbum->creatable->id === $user->id) || ($photoAlbum->author_id === $user->id)) {
                $photoAlbum->delete();
            }
        }

        return response()->json([
            'message' => 'Фотоальбом успешно удален.',
        ]);
    }
}
