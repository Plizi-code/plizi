<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\UploadFileRequest;
use App\Http\Resources\Post\AttachmentsCollection;
use App\Http\Resources\Post\Post as PostResource;
use App\Http\Requests\Post\Post as PostRequest;
use App\Http\Resources\Post\PostCollection;
use App\Http\Resources\User\SimpleUsers;
use App\Models\Community;
use App\Models\Post;
use App\Models\PostAttachment;
use App\Models\User;
use App\Models\View;
use App\Services\S3UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class PostController extends Controller
{

    public $uploadService;

    public function __construct(S3UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    /**
     * @param Request $request
     * @return PostCollection
     */
    public function index(Request $request) {
        $posts = \Auth::user()->posts()
            ->limit($request->query('limit') ?? 50)
            ->offset($request->query('offset') ?? 0)
            ->get();

        return new PostCollection($posts, false);
    }

    /**
     * @param Request $request
     * @return PostCollection
     */
    public function myPosts(Request $request) {
        $posts = Post::getWithoutOldPosts(\Auth::user(), $request->query('limit'), $request->query('offset'), true);

        return new PostCollection($posts);
    }

    /**
     * @param Request $request
     * @return PostCollection
     */
    public function getNews(Request $request)
    {
        $posts = Post::getWithoutOldPosts(
            \Auth::user(),
            $request->query('limit'),
            $request->query('offset'),
            false,
            $request->get('onlyLiked', false),
            $request->get('orderBy'),
            $request->get('search'),
            $request->get('parts')
        );

        return new PostCollection($posts);
    }

    /**
     * @param Request $request
     * @param $id
     * @return PostCollection|\Illuminate\Http\JsonResponse
     */
    public function userPosts(Request $request, $id) {
        $user = User::find($id);
        $posts = Post::getWithoutOldPosts($user, $request->query('limit'), $request->query('offset'), true);

        return new PostCollection($posts);
    }

    /**
     * @param Request $request
     * @return PostCollection
     */
    public function communityPosts(Request $request) {
        /** @var Community $community */
        $community = $request->community;

        $posts = $community->posts()->with(['postable', 'author', 'usersLikes' => static function ($query) {
            return $query->limit(8)->get();
        }])
            ->withCount('comments')
            ->limit($request->query('limit', 50))
            ->offset($request->query('offset', 0))
            ->orderByDesc('id')
            ->get();
        return new PostCollection($posts);
    }

    /**
     * @param $id
     * @return PostResource|\Illuminate\Http\JsonResponse
     */
    public function get($id) {
        $post = Post::find($id);
        if($post) {
            return new PostResource($post);
        }
        return response()->json(['message' => 'Пост не найден'], 404);
    }

    /**
     * @param PostRequest $request
     * @return PostResource
     */
    public function storeByUser(PostRequest $request) {
        $post = \Auth::user()->posts()->create([
            'name' => $request->name,
            'body' => $request->body ?: '',
            'author_id' => \Auth::user()->id
        ]);
        if(isset($request->attachmentIds) && count($request->attachmentIds)) {
            PostAttachment::whereIn('id', $request->attachmentIds)->update(['post_id' => $post->id]);
        }
        Event::dispatch('user.post.created', ['user_id' => \Auth::user()->id, 'post' => $post]);
        return new PostResource($post);
    }

    /**
     * @param PostRequest $request
     * @param $community_id
     * @return PostResource|\Illuminate\Http\JsonResponse
     */
    public function storeByCommunity(PostRequest $request, $community_id) {
        $community = Community::with('users')->find($community_id);
        if($community) {
            if($community->users->contains(auth()->user()->id)) {
                $post = $community->posts()->create([
                    'name' => $request->name,
                    'body' => $request->body ?: '',
                    'author_id' => \Auth::user()->id
                ]);
                if(isset($request->attachmentIds) && count($request->attachmentIds)) {
                    PostAttachment::whereIn('id', $request->attachmentIds)->update(['post_id' => $post->id]);
                }
                Event::dispatch('community.post.created', ['community' => $community, 'post' => $post]);
                return new PostResource($post);
            } else {
                return response()->json(['message' => 'Вы не являетесь участником данного сообщества'], 422);
            }
        }
        return response()->json(['message' => 'Сообщество не найдено'], 404);
    }

    /**
     * @param Request $request
     * @return PostResource
     */
    public function addToMyPosts(Request $request)
    {
        $post = Post::find($request->id);
        $my_post = $post->replicate();
        $my_post->postable_type = User::class;
        $my_post->postable_id = \Auth::user()->id;
        $my_post->parent_id = $post->id;
        $my_post->body = '';
        $my_post->likes = 0;
        $my_post->views = 0;
        $my_post->author_id = \Auth::user()->id;
        $my_post->save();
        return new PostResource($my_post);
    }

    /**
     * @param UploadFileRequest $request
     * @return AttachmentsCollection
     * @throws \Exception
     */
    public function uploadAttachments(UploadFileRequest $request) {
        $attachment_ids = $this->uploadService->uploadFiles(new PostAttachment(), 'post/attachments', $request->allFiles());
        $attachments = PostAttachment::whereIn('id', $attachment_ids)->get();
        return new AttachmentsCollection($attachments);
    }

    /**
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Post $post)
    {
        if ($post->userHasAccess()) {
            $post->delete();

            return response()->json([
                'message' => 'Вы успешно удалили запись.',
            ]);
        }

        return response()->json([
            'message' => 'Запись не найдена.',
        ], 404);
    }

    /**
     * @param Post $post
     * @param PostAttachment $postAttachment
     * @return PostResource|\Illuminate\Http\JsonResponse
     */
    public function deleteImage(Post $post, PostAttachment $postAttachment)
    {
        $user_post = \Auth::user()->posts()->where('id', $post->id)->get();

        if ($user_post) {
            $post->attachments()->where('id', $postAttachment->id)->delete();

            return new PostResource($post);
        }

        return response()->json([
            'message' => 'Запись не найдена.',
        ], 404);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($id)
    {
        $post = Post::withTrashed()
            ->where('id', $id)
            ->first();

        if ($post->userHasAccess()) {
            $post->restore();

            return response()->json([
                'message' => 'Вы успешно восстановили запись.',
            ]);
        }

        return response()->json([
            'message' => 'Запись не найдена.',
        ], 404);
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return PostResource|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Post $post)
    {
        if ($post->author->id === \Auth::user()->id) {
            $post->update(['body' => $request->body]);

            if ($request->attachmentIds) {
                PostAttachment::whereIn('id', $request->attachmentIds)->update(['post_id' => $post->id]);
            }

            return new PostResource($post);
        }

        return response()->json([
            'message' => 'Запись не найдена.',
        ], 404);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markViewed(Request $request) {
        View::create([
            'user_id' => \Auth::user()->id,
            'viewable_type' => Post::class,
            'viewable_id' => $request->get('postId')
        ]);
        return response()->json([
            'data' => [
                'message' => 'Просмотрено'
            ]
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return SimpleUsers
     */
    public function getViewedUsers(Request $request, $id) {
        $views = View::with('user')
            ->select('user_id', 'viewable_id', 'viewable_type')
            ->where('viewable_id', $id)
            ->where('viewable_type', Post::class)
            ->groupBy('user_id', 'viewable_id', 'viewable_type')
            ->limit(5)
            ->get();
        return new SimpleUsers($views->pluck('user'));
    }
}
