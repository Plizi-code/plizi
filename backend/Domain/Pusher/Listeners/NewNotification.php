<?php


namespace Domain\Pusher\Listeners;

use App\Http\Resources\Notification\Notification;
use App\Notifications\UserSystemNotifications;
use Domain\Pusher\WampServer as Pusher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Events\NotificationSent;

class NewNotification implements ShouldQueue
{

    use Queueable;

    /**
     * @param NotificationSent $event
     */
    public function handle(NotificationSent $event)
    {
        if($event->response && $event->response->type === UserSystemNotifications::class) {
            Pusher::sentDataToServer([
                'data' => new Notification($event->response),
                'topic_id' => Pusher::channelForUser($event->response->notifiable_id),
                'event_type' => 'user.notification']);
        }
    }
}
