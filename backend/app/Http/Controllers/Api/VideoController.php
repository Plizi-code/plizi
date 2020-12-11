<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Video\VideoStore;
use App\Http\Resources\Video\VideoCollection;
use App\Models\Community;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getUserVideo(Request $request)
    {
        /** @var User $user */
        $user = $request->user ?: auth()->user();
        $videos = $user->videos()->with(['creatableby' => static function ($query) use ($user) {
            if ($user->id === auth()->id()) {
                $query->withTrashed()->get();
            }
        }])
            ->limit($request->query('limit', 20))
            ->offset($request->query('offset', 0))
            ->latest()
            ->get();

        return new VideoCollection($videos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(VideoStore $request)
    {
        $user = auth()->user();
        $type_model = null;

        switch ($request->workMode) {
            case 'post':
                $type_model .= 'App\Models\Post';
                break;
        }

        $video = Video::create([
            'user_id' => $user->id,
            'link' => $request->link,
            'creatableby_id' => $request->id,
            'creatableby_type' => $type_model,
        ]);

        if ($video) {
            auth()->user()->profile()->increment('video_count');
        }
        if ($video->creatableby && (($community = $video->creatableby->postable) instanceof Community)) {
            $community->increment('video_count');
        }
        return response()->json([
            'data' => [
                'id' => $video->id,
            ],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Video $video
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Video $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Video $video)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Video $video
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Video $video)
    {
        if ($video->user->id === \Auth::id()) {
            auth()->user()->profile()->decrement('video_count');
            if ($video->creatableby && (($community = $video->creatableby->postable) instanceof Community)) {
                $community->decrement('video_count');
            }
            $video->delete();

            return response()->json([
                'message' => 'Видео успешно удалено.',
            ]);
        }

        return response()->json([
            'message' => 'Ошибка удаления видео.',
        ], 403);
    }
}
