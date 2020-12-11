<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Domain\Pusher\Http\Requests\OpenChat;
use Domain\Pusher\Http\Requests\SendMessageRequest;
use Domain\Pusher\Http\Requests\SendMessageToUserRequest;
use Domain\Pusher\Http\Requests\UploadFileRequest;
use Domain\Pusher\Repositories\ChatRepository;
use Domain\Pusher\Repositories\MessageRepository;
use Domain\Pusher\Services\ChatService;
use Auth;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * @var ChatRepository
     */
    protected $repository;

    /**
     * @var MessageRepository
     */
    protected $messageRepository;

    /**
     * @var ChatService
     */
    protected $chatService;

    /**
     * ChatController constructor.
     * @param ChatRepository $repository
     * @param MessageRepository $messageRepository
     * @param ChatService $chatService
     */
    public function __construct(ChatRepository $repository, MessageRepository $messageRepository, ChatService $chatService)
    {
        $this->repository = $repository;
        $this->messageRepository = $messageRepository;
        $this->chatService = $chatService;
        parent::__construct();
    }


    /**
     * Возвращает список диалогов
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dialogs(Request $request)
    {
        $dialogs = $this->repository->getChatsByUserId(Auth::user()->id, $request->query('search'));
        return response()->json($dialogs);
    }

    /**
     * @param Request $request
     * @param string $chat_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function messages(Request $request, string $chat_id)
    {
        $messages = $this->messageRepository->getAllOfChatById(
            $chat_id,
            Auth::user()->id,
            $request->query('limit') ?? 50,
            $request->query('offset') ?? 0,
            $request->query('search') ?? null,
            $request->query('dateStart') ?? null,
            $request->query('dateEnd') ?? null
        );
        return response()->json($messages);
    }

    /**
     * @param OpenChat $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function open(OpenChat $request)
    {
        $chat = $this->chatService->getChatForUsers(
            $request->get('userIds'),
            $request->get('name')
        );
        return response()->json(['data' => $chat]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function openWithCommunityAdmins(Request $request)
    {
        $community = Community::find($request->get('communityId'));
        $author = $community->authors()->first();
        $admin_ids = $community->admins()->get()->pluck('id')->toArray();
        $chat = $this->chatService->openWithCustomAdmin(
            array_merge($admin_ids, [Auth::user()->id]),
            $author->id,
            $request->get('name')
        );
        return response()->json(['data' => $chat]);
    }

    /**
     * Отправка сообщения в чат
     * @param SendMessageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(SendMessageRequest $request)
    {
        $message = $this->chatService->send(
            $request->get('body'),
            $request->get('chatId'),
            auth()->id(),
            $request->get('replyOnMessageId'),
            $request->get('forwardFromChatId'),
            $request->get('attachments') ? $request->get('attachments') : []
        );
        return response()->json(['data' => [
            'id' => $message
        ]]);
    }

    /**
     * @param SendMessageToUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendToUser(SendMessageToUserRequest $request)
    {
        $this->chatService->sendToUser(
            $request->get('body'),
            $request->get('userId'),
            auth()->id(),
            $request->get('replyOnMessageId'),
            $request->get('forwardFromChatId'),
            $request->get('attachments') ? $request->get('attachments') : []
        );
        return response()->json(['data' => [
            'success' => true
        ]]);
    }

    /**
     * Добавление пользователя в чат
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function appendUserToChat(Request $request)
    {
        $chat = $this->chatService->appendUserToChat(
            $request->get('chatId'),
            $request->get('userId'),
        );
        if($chat) {
            return response()->json(['data' => $chat]);
        }
        return response()->json([
            'message' => "Вы не можете добавлять людей в данный чат"
        ], 403);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeUserFromChat(Request $request) {
        $chat = $this->chatService->removeUserFromChat(
            $request->get('chatId'),
            $request->get('userId'),
            );
        if($chat) {
            return response()->json(['data' => $chat]);
        }
        return response()->json([
            'message' => "Вы не можете удалять людей из данного чата"
        ], 403);
    }

    /**
     * @param UploadFileRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function uploadAttachments(UploadFileRequest $request) {
        $attachments = $this->chatService->uploadFiles($request->allFiles());
        return response()->json([
            'data' => $attachments
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return bool|\Domain\Pusher\Http\Resources\Message\Message|\Illuminate\Http\JsonResponse
     */
    public function destroyMessage(Request $request, $id) {
        if($data = $this->chatService->destroyMessage($id)) {
            return response()->json(['data' => $data], 200);
        }
        return response()->json(['message' => 'Вы не можете удалить чужое сообщение'], 422);
    }

    /**
     * @param Request $request
     * @param $id
     * @return bool|\Domain\Pusher\Http\Resources\Message\Message|\Illuminate\Http\JsonResponse
     */
    public function destroyChat(Request $request, $id) {
        if($id = $this->chatService->destroyChat($id)) {
            return response()->json(['data' => ['id' => $id]], 200);
        }
        return response()->json(['message' => 'Вы не можете удалить чужой чат'], 422);
    }
}
