<?php


namespace Domain\Pusher\Repositories;


use Carbon\Carbon;
use Clockwork\Request\Log;
use Domain\Pusher\Http\Resources\Message\MessageCollection;
use Domain\Pusher\Models\ChatMessage;
use Domain\Pusher\Http\Resources\Message\Message as MessageResource;

class MessageRepository
{

    /**
     * Сохраняет сообщениие
     *
     * @param string $chat_id
     * @param string $body
     * @param string $author_id
     * @param string|null $parent_id
     * @param string|null $parent_chat_id
     * @return string
     */
    public function saveInChatById(string $chat_id, string $body, string $author_id, string $parent_id = null, string $parent_chat_id = null) : string
    {
        return ChatMessage::create([
                'chat_id' => $chat_id,
                'body' => $body,
                'user_id' => $author_id,
                'parent_id' => $parent_id,
                'parent_chat_id' => $parent_chat_id,
            ])->id;
    }

    /**
     * @param string $chat_id
     * @param string|null $user_id
     * @param int $limit
     * @param int $offset
     * @param null $search
     * @param null $date_start
     * @param null $date_end
     * @return MessageCollection
     */
    public function getAllOfChatById(string $chat_id, string $user_id = null, $limit = 50, $offset = 0, $search = null, $date_start = null, $date_end = null)
    {
        $items = ChatMessage::with('user', 'attachments')->where('chat_id', $chat_id)
            ->orderBy('created_at', 'desc')
            ->limit((int)$limit)
            ->offset((int)$offset);

        if($search) {
            $items->where('body', 'LIKE', "%{$search}%");
        }
        if($date_start && $date_end) {
            $items = $items->whereBetween('created_at', [Carbon::createFromTimestamp($date_start), Carbon::createFromTimestamp($date_end)]);
        }
        $items = $items->get();
        return new MessageCollection($items, $user_id);
    }


    /**
     * Возвращает сообщение
     *
     * @param string $message_id
     * @return MessageResource
     */
    public function getMessageById(string $message_id, $user_id = null)
    {
        $item = ChatMessage::with('user')->find($message_id);
        if($item) {
            return new MessageResource($item, $user_id ? $user_id :\Auth::user()->id);
        }
        return null;
    }

    /**
     * @param string $message_id
     * @return bool
     */
    public function destroyMessage(string $message_id) {
        ChatMessage::destroy($message_id);
        return true;
    }
}
