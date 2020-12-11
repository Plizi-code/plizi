<?php


namespace Domain\Pusher\Listeners;

use Domain\Pusher\Events\NewMessageEvent;
use Domain\Pusher\Models\ChatMessageAttachment;
use Domain\Pusher\Repositories\ChatRepository;
use Domain\Pusher\Repositories\MessageRepository;
use Domain\Pusher\WampServer as Pusher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewMessageNotification implements ShouldQueue
{

    use Queueable;

    /**
     * @param NewMessageEvent $event рассылка сообщений
     */
    public function handle(NewMessageEvent $event)
    {
        $chatRepo = new ChatRepository();
        $messageRepo = new MessageRepository();
        $author_id = $event->getAuthorId();
        $body = $event->getBody();
        $attachments = $event->getAttachments();
        $chat_id = $event->getChatId();
        if (!$chat_id && $toUserId = $event->getToUserId()) {
            $chat_id = $chatRepo->getOrCreateChat($toUserId, $author_id);
        }
        $parent_id = $event->getParentId();
        $parent_chat_id = $event->getParentChatId();
        $message_id = $messageRepo->saveInChatById($chat_id, $body, $author_id, $parent_id, $parent_chat_id);
        if(count($attachments)) {
            ChatMessageAttachment::whereIn('_id', $attachments)->update(["chat_message_id" => $message_id]);
        }

        $users_list = $chatRepo->getUsersIdListFromChat($chat_id);
        $message = $messageRepo->getMessageById($message_id, $author_id);
        foreach ($users_list as $user_id) {
            $message = json_decode(json_encode($message), true);
            $message['isMine'] = $message['userId'] === $user_id ? true : false;
            Pusher::sentDataToServer(['data' => $message, 'topic_id' => Pusher::channelForUser($user_id), 'event_type' => 'message.new']);
        }
    }
}
