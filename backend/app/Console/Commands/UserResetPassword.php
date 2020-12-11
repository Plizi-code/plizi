<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UserResetPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:password {user} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change user password';

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
        $user = $this->argument('user');
        $password = $this->argument('password');
        $field = 'email';
        if (is_numeric($user)) {
            $field = 'id';
        }
        if (\DB::table('users')->where($field, $user)->update(['password' => Hash::make($password)])) {
            $this->info('The new password for user with ' . $field . ' ' . $user . ' was created: ' . $password);
        } else {
            $this->info('Something went wrong(');
        }
    }
}
