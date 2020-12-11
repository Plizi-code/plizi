<?php


namespace Domain\Pusher\Listeners;

use Domain\Pusher\Events\DestroyMessageEvent;
use Domain\Pusher\Events\NewMessageEvent;
use Domain\Pusher\WampServer as Pusher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class DestroyMessageNotification implements ShouldQueue
{

    use Queueable;

    /**
     * @param DestroyMessageEvent $event
     */
    public function handle(DestroyMessageEvent $event)
    {
        $idsOfUsers = $event->getUsersListIds();
        $body = $event->getMessage();
        foreach ($idsOfUsers as $user_id) {
            Pusher::sentDataToServer(['data' => $body, 'topic_id' => Pusher::channelForUser($user_id), 'event_type' => 'message.deleted']);
        }
    }
}
