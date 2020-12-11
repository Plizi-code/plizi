<?php


namespace Domain\Pusher\Commands;

use Domain\Pusher\WampServer as Pusher;
use Illuminate\Console\Command;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\Wamp\WampServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory as ReactLoop;
use React\Socket\SecureServer;
use React\Socket\Server;
use React\ZMQ\Context as ReactContext;


class RunPusherCommand extends Command
{
    protected $signature = 'ws:serve';

    protected $description = 'Run a push server';

    public function handle()
    {
        $loop = ReactLoop::create();
        $pusher = new Pusher();

        $context = new ReactContext($loop);
        $pull = $context->getSocket(\ZMQ::SOCKET_PULL);
        $pull->bind("tcp://0.0.0.0:5555");
        $pull->on('message', [$pusher, 'broadcast']);

        if(config('app.secure_wss')) {
            $webSock = new SecureServer(
                new Server('0.0.0.0:7070', $loop),
                $loop,
                [
                    'local_cert'        => config('app.fullchain_path'),
                    'local_pk'          => config('app.private_key_path'),
                    'allow_self_signed' => TRUE,
                    'verify_peer' => FALSE
                ]
            );
        } else {
            $webSock = new Server("0.0.0.0:7070",$loop);
        }
        $server = new IoServer(
            new HttpServer(
                new WsServer(
                    new WampServer(
                    $pusher
                )
            )
        ), $webSock, $loop);
        $this->info("Listen on: {$webSock->getAddress()}");
        $server->run();
    }
}
