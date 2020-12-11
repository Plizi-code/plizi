<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PHPSocketIO\SocketIO;
use Workerman\Worker;

class StartServerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $io = new SocketIO(2021);
        $io->on('connection', function ($socket) use ($io) {
            $socket->on('chat message', function ($msg) use ($io) {
                $io->emit('chat message', $msg);
            });
        });
        Worker::runAll();
    }
}
