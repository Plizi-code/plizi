<?php

namespace App\Console\Commands;

use App\Models\User;
use Domain\Pusher\Models\Chat;
use Faker\Provider\ru_RU\Text;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Expression;
use Faker\Factory as Faker;

class GenerateMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:messages {chat_id} {messages}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set or unset user as admin by ID or email';

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
        $chat_id = $this->argument('chat_id');
        $messages_count = $this->argument('messages');
        $faker = Faker::create('ru_RU');
        $attendees = \DB::table('chat_party')->where('chat_id', $chat_id)->pluck('user_id');
        $messages = [];
        for ($i = 0; $i <= $messages_count; $i++) {
            foreach ($attendees as $attendee) {
                $messages[] = [
                    'chat_id' => $chat_id, 'user_id' => $attendee, 'body' =>  $faker->realText(70), 'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now')->format('U'), 'updated_at' => time()
                ];
            }
        }
        \DB::table('chat_messages')->insert($messages);
        $this->info('Generated');
    }
}
