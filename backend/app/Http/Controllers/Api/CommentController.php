<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Post\UploadFileRequest;
use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\Post\AttachmentsCollection;
use App\Http\Resources\Post\Post as PostResource;
use App\Models\Comment;
use App\Http\Resources\Comment\Comment as CommentResource;
use App\Models\CommentAttachment;
use App\Models\Community;
use App\Models\ImageUpload;
use App\Models\Post;
use App\Models\PostAttachment;
use App\Models\User;
use App\Services\S3UploadService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public $uploadService;

    public function __construct(S3UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function comment($model, $id, Request $request)
    {
        $comment_id = Comment::insertGetId([
            'body' => $request->get('body'),
            'author_id' => \Auth::user()->id,
            'commentable_id' => $id,
            'commentable_type' => $model,
            'reply_on' => $request->get('replyOn'),
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        if(isset($request->attachmentIds) && count($request->attachmentIds)) {
            CommentAttachment::whereIn('id', $request->attachmentIds)->update(['comment_id' => $comment_id]);
        }

        return Comment::with('author', 'attachments')->find($comment_id);
    }

    /**
     * @param Request $request
     * @return CommentResource
     */
    public function commentPost(Request $request) {
        $comment_id = Comment::insertGetId([
            'body' => $request->get('body'),
            'author_id' => \Auth::user()->id,
            'commentable_id' => $request->get('postId'),
            'commentable_type' => Post::class,
            'reply_on' => $request->get('replyOn'),
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        if(isset($request->attachmentIds) && count($request->attachmentIds)) {
            CommentAttachment::whereIn('id', $request->attachmentIds)->update(['comment_id' => $comment_id]);
        }
        $comment = Comment::with('author', 'attachments')->find($comment_id);
        return new CommentResource($comment);
    }

    /**
     * @param Request $request
     * @param $id
     * @return CommentCollection
     */
    public function getPostComments(Request $request, $id) {
        $comments = Post::find($id)->comments()->with('author', 'author.profile', 'author.profile.avatar', 'attachments')->get();
        return new CommentCollection($comments);
    }

    /**
     * @param Request $request
     * @param Comment $comment
     * @return CommentResource|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Comment $comment) {
        if ($comment->author_id === \Auth::user()->id) {
            $comment->update(['body' => $request->body]);
            if(isset($request->attachmentIds) && count($request->attachmentIds)) {
                CommentAttachment::whereIn('id', $request->attachmentIds)->update(['comment_id' => $comment->id]);
            }

            return new CommentResource($comment);
        }

        return response()->json([
            'message' => 'Комментарий не найден.',
        ], 404);
    }

    /**
     * @param UploadFileRequest $request
     * @return AttachmentsCollection
     * @throws \Exception
     */
    public function uploadAttachments(UploadFileRequest $request) {
        $attachment_ids = $this->uploadService->uploadFiles(new CommentAttachment(), 'comment/attachments', $request->allFiles());
        $attachments = CommentAttachment::whereIn('id', $attachment_ids)->get();
        return new AttachmentsCollection($attachments);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyComment(Request $request, $id) {
        $comment = Comment::find($id);
        if($comment->author_id === \Auth::user()->id) {
            $comment->delete();
            return response()->json([
                'data' => [
                    'id' => $id
                ]
            ]);
        }
        if($comment->commentable instanceof Post) {
            if($comment->commentable->postable instanceof User) {
                if($comment->commentable->postable->id === \Auth::user()->id) {
                    $comment->delete();
                    return response()->json([
                        'data' => [
                            'id' => $id
                        ]
                    ]);
                }
            } else if($comment->commentable->postable instanceof Community) {
                if($comment->commentable->postable->authors->contains(\Auth::user()) || $comment->commentable->postable->admins->contains(\Auth::user())) {
                    $comment->destroy();
                    return response()->json([
                        'data' => [
                            'id' => $id
                        ]
                    ]);
                }
            }
        }
        return response()->json([
            'message' => "Вы не можете удалить данный комментарий"
        ], 403);
    }

    public function commentPostImage(PostAttachment $postAttachment, Request $request)
    {
        $comment = $this->comment(PostAttachment::class, $postAttachment->id, $request);

        return new CommentResource($comment);
    }

    public function commentUserImage(ImageUpload $imageUpload, Request $request)
    {
        $comment = $this->comment(ImageUpload::class, $imageUpload->id, $request);

        return new CommentResource($comment);
    }

    public function getCommentPostImage(PostAttachment $postAttachment)
    {
        $comments = $postAttachment->comments()
            ->with('author', 'author.profile', 'author.profile.avatar', 'attachments')
            ->get();

        return new CommentCollection($comments);
    }

    public function getCommentUserImage(ImageUpload $imageUpload)
    {
        $comments = $imageUpload->comments()
            ->with('author', 'author.profile', 'author.profile.avatar', 'attachments')
            ->get();

        return new CommentCollection($comments);
    }
}
