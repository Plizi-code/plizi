<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\SimpleUsers;
use App\Models\Comment;
use App\Models\ImageUpload;
use App\Models\Like;
use App\Models\Post;
use App\Models\PostAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class LikeController extends Controller
{
    public function like($model, $id)
    {
        $isExistLike = Like::where('user_id', \Auth::user()->id)
            ->where('likeable_type', $model)
            ->where('likeable_id', $id)
            ->exists();

        if (!$isExistLike) {
            $like = Like::create([
                'user_id' => \Auth::user()->id,
                'likeable_type' => $model,
                'likeable_id' => $id,
            ]);

            return $like;
        } else {
            Like::where('user_id', \Auth::user()->id)
                ->where('likeable_type', $model)
                ->where('likeable_id', $id)
                ->first()
                ->delete();

            return false;
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function likePost(Request $request)
    {
        $like = $this->like(Post::class, $request->postId);

        if ($like) {
            if ($like->likeable->author_id !== \Auth::user()->id) {
                Event::dispatch('post.liked', ['post_id' => $request->postId, 'user_id' => \Auth::user()->id]);
            }

            return response()->json(['message' => 'Вы успешно оценили данную запись']);
        }

        return response()->json(['message' => 'Вы успешно сняли свою оценку с данной записи']);
    }

    /**
     * @param Post $post
     * @param Request $request
     * @return SimpleUsers
     */
    public function getPostUsersLikes(Post $post, Request $request)
    {
        $usersLikes = $post->usersLikes()
            ->limit($request->query('limit') ?? 20)
            ->offset($request->query('offset') ?? 0)
            ->get();

        return new SimpleUsers($usersLikes);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function likePostImage(Request $request)
    {
        $like = $this->like(PostAttachment::class, $request->imageId);

        if ($like) {
            return response()->json(['message' => 'Вы успешно оценили данную запись']);
        }

        return response()->json(['message' => 'Вы успешно сняли свою оценку с данной записи']);
    }

    /**
     * @param Comment $comment
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function likeComment(Comment $comment, Request $request)
    {
        $like = $this->like(Comment::class, $comment->id);

        if ($like) {
            return response()->json(['message' => 'Вы успешно оценили данную запись']);
        }

        return response()->json(['message' => 'Вы успешно сняли свою оценку с данной записи']);
    }

    /**
     * @param ImageUpload $imageUpload
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function likeUserImage(ImageUpload $imageUpload, Request $request)
    {
        $like = $this->like(ImageUpload::class, $imageUpload->id);

        if ($like) {
            return response()->json(['message' => 'Вы успешно оценили данную запись']);
        }

        return response()->json(['message' => 'Вы успешно сняли свою оценку с данной записи']);
    }
}
