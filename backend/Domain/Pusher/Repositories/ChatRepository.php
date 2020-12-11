<?php


namespace Domain\Pusher\Repositories;


use Domain\Pusher\Events\ChatActionEvent;
use Domain\Pusher\Http\Resources\Chat\ChatCollection;
use Domain\Pusher\Models\Chat;
use Domain\Pusher\Http\Resources\Chat\Chat as ChatResource;
use Domain\Pusher\Models\User;

class ChatRepository
{

    /**
     * Возвращает список чатов пользователя
     *
     * @param int $user_id
     * @param $search
     * @return ChatCollection
     */
    public function getChatsByUserId(string $user_id, $search = null)
    {
        $query = Chat::with(['attendees' => function($query) use ($user_id) {
            $query->where('id', '<>', $user_id);
        }])->whereHas('attendees', function($user) use ($user_id) {
            $user->where('id', '=', $user_id);
        });

        if(!empty($search) && strlen($search) > 3) {
            $query->whereHas('attendees', function($user) use ($search, $user_id) {
                $user->where('profile.first_name', 'LIKE', "%{$search}%")
                    ->orWhere('profile.last_name', 'LIKE', "%{$search}%")
                    ->where('id', '<>', $user_id);
            });
        }

        $items = $query->orderBy('last_message_time', 'asc')->get();
        $items = $items->filter(function ($item) {
            return count($item->attendees);
        });
        return new ChatCollection($items, $user_id);
    }

    /**
     * Получение чата по ID
     *
     * @param string $id
     * @return ChatResource
     */
    public function getChatById(string $id)
    {
        $user_id = \Auth::user()->id;
        $items = Chat::with(['attendees' => function($query) use ($user_id) {
            $query->where('id', '<>', $user_id);
        }])->where('_id', $id)
            ->orderBy('last_message_time', 'desc')
            ->first();

        return new ChatResource($items, $user_id);
    }

    /**
     * Получение чата по ID
     *
     * @param string $id
     * @return ChatResource
     */
    public function getChatByIdWithAllAttendees(string $id)
    {
        $items = Chat::with(['attendees'])->where('_id', $id)
            ->orderBy('last_message_time', 'desc')
            ->first();

        return new ChatResource($items);
    }

    /**
     * Возвращает список идентификаторов пользователей,
     * которые являются участниками чата. Если указана
     * $exclude_id, этот пользователь будет исключен из результатирующего
     * набора. Это может быть полезным, при оповещении всех участников
     * чата о новом сообщении, кроме самого автора.
     * @param string $chat_id идентификатор чата
     * @param string $exclude_id исключаемый пользователь
     * @return array список ID пользователей участников
     */
    public function getUsersIdListFromChat(string $chat_id, string $exclude_id = null): array
    {
        $chat = Chat::where('_id', $chat_id)->first();
        return array_filter($chat->user_ids, function ($element) use ($exclude_id) { return ($element != $exclude_id); } );
    }

    /**
     * Возвращает ID чата если он уже существует
     * для пары пользователей который были
     * переданы в параметрах
     *
     * @param $first_user
     * @param $second_user
     * @return mixed|null
     */
    public function getChatIdForCoupleUsers($first_user, $second_user) {
        $chat = Chat::with('attendees')
            ->where('user_ids', 'all', [$first_user, $second_user])
            ->where('user_ids', 'size', 2)
            ->first();
        if($chat) {
            return $chat->id;
        }
        return null;
    }

    /**
     * @param $user_ids
     * @return mixed|null
     */
    public function getChatIdForGroupUsers($user_ids) {
        $chat = Chat::with('attendees')
            ->where('user_ids', 'all', $user_ids)
            ->where('user_ids', 'size', count($user_ids))
            ->first();
        if($chat) {
            return $chat->id;
        }
        return null;
    }

    /**
     * Создает чат для двух пользователей
     *
     * @param $receiver_id
     * @param $author_id
     * @return int
     */
    public function createChatForCoupleUsers($receiver_id, $author_id) {
        $chat_id = Chat::insertGetId(['user_id' => $author_id]);
        /** @var Chat $chat */
        $chat = Chat::find($chat_id);
        $chat->attendees()->attach($author_id);
        $chat->attendees()->attach($receiver_id);
        event(new ChatActionEvent($this->getUsersIdListFromChat($chat_id, \Auth::user()->id), 'chat.created', $this->getChatByIdWithAllAttendees($chat_id)));
        return $chat_id;
    }

    /**
     * Создает чат для нескольких пользователей
     *
     * @param $receiver_ids
     * @param $author_id
     * @param string $name
     * @return int
     */
    public function createChatForUsers($receiver_ids, $author_id, $name = '') {
        /** @var Chat $chat */
        $chat = Chat::create([
            'user_id' => $author_id,
            'name' => $name,
        ]);
        $chat->attendees()->attach($author_id);
        $receiver_ids = User::whereIn('id', $receiver_ids)->get()->pluck('id')->toArray();
        foreach ($receiver_ids as $receiver_id) {
            $chat->attendees()->attach($receiver_id);
        }
        $chat_id = $chat->refresh()->id;
        event(new ChatActionEvent($this->getUsersIdListFromChat($chat_id, $author_id), 'chat.created', $this->getChatByIdWithAllAttendees($chat_id)));
        return $chat_id;
    }

    /**
     * @param $user_id
     * @param $chat_id
     * @return bool
     */
    public function isUserInChat($user_id, $chat_id) {
        $chat = Chat::where('_id', $chat_id)->first();
        return in_array($user_id, $chat->user_ids);
    }

    /**
     * @param $chat_id
     * @param $user_id
     * @return ChatResource
     */
    public function insertToChartParty($chat_id, $user_id) {
        /** @var Chat $chat */
        $chat = Chat::find($chat_id);
        $chat->attendees()->attach($user_id);
        event(new ChatActionEvent($this->getUsersIdListFromChat($chat_id, \Auth::user()->id), 'chat.attendee.appended', $this->getChatByIdWithAllAttendees($chat_id)));
        return $this->getChatById($chat_id);
    }

    /**
     * @param $chat_id
     * @param $user_id
     * @return ChatResource
     */
    public function removeFromChartParty($chat_id, $user_id) {
        /** @var Chat $chat */
        $chat = Chat::find($chat_id);
        event(new ChatActionEvent($this->getUsersIdListFromChat($chat_id, \Auth::user()->id), 'chat.attendee.removed', ['id' => $chat_id, 'userId' => $user_id]));
        $chat->attendees()->detach($user_id);
        return $this->getChatById($chat_id);
    }

    /**
     * @param string $chat_id
     * @return bool
     */
    public function destroyChat(string $chat_id) {
        event(new ChatActionEvent($this->getUsersIdListFromChat($chat_id, \Auth::user()->id), 'chat.removed', ['id' => $chat_id]));
        Chat::destroy($chat_id);
        return true;
    }

    /**
     * Найти или создать персональный чат
     * @param $first_user
     * @param $second_user
     * @return int|mixed|null
     */
    public function getOrCreateChat($first_user, $second_user)
    {
        if (!$chat_id = $this->getChatIdForCoupleUsers($first_user, $second_user)) {
            $chat_id = $this->createChatForUsers([$first_user], $second_user);
        }
        return $chat_id;
    }
}
