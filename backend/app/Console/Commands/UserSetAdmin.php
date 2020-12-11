<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Expression;

class UserSetAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:admin {user}';

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
        $user = $this->argument('user');
        $field = 'email';
        if (is_numeric($user)) {
            $field = 'id';
        }
        if (\DB::table('users')->where($field, $user)->update(['is_admin' => new Expression('ABS(is_admin - 1)')])) {
            $this->info(
                'User with ' . $field . ' ' . $user . (
                    User::where($field, $user)->get()->first()->isAdmin() ? ' is admin now' : ' is not admin anymore'
                )
            );
        } else {
            $this->info('Something went wrong(');
        }
    }
}
