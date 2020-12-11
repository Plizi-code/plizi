<?php


namespace Domain\Pusher\Commands;


use Domain\Pusher\WampServer as Pusher;
use Illuminate\Console\Command;

class SendMessageCommand extends Command
{
    protected $signature = 'ws:send {message} {user_id}';

    protected $description = "Sent message to ws сервер";


    public function handle()
    {
        $channel = Pusher::channelForUser($this->argument('user_id'));
        Pusher::sentDataToServer(['data' => $this->argument('message'), 'topic_id' => $channel]);
        $this->info("Message sent to channel: $channel");
    }
}
