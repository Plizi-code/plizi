<?php


namespace Domain\Pusher\Listeners;

use App\Models\User;
use Carbon\Carbon;
use Domain\Pusher\Events\ChatActionEvent;
use Domain\Pusher\Models\Profile;
use Domain\Pusher\WampServer as Pusher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChatActionListener implements ShouldQueue
{
    use Queueable;

    public function handle(ChatActionEvent $event) {
        $idsOfUsers = $event->getUserIds();
        $data = $event->getData();
        $action = $event->getAction();
        foreach ($idsOfUsers as $user_id) {
            Pusher::sentDataToServer(['data' => $data, 'topic_id' => Pusher::channelForUser($user_id), 'event_type' => $action]);
        }
    }
}
