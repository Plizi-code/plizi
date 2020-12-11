<?php

use Illuminate\Database\Seeder;

use App\Models\Profile;
use App\Models\User;
use App\Models\Rbac\Role;
use App\Models\Rbac\Permission;

class BaseRBACSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = new Permission();
        $permission->name = 'viewPage';
        $permission->display_name = 'View page';
        $permission->description  = 'Просмотр страницы';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'sendMessage';
        $permission->display_name = 'Send message';
        $permission->description  = 'Отправить сообщение';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'viewWall';
        $permission->display_name = 'View wall';
        $permission->description  = 'Просмотреть стену';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'postOnWall';
        $permission->display_name = 'Post on wall';
        $permission->description  = 'Запостить на стену';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'viewFriendsList';
        $permission->display_name = 'View friends list';
        $permission->description  = 'Просмотреть список друзей';
        $permission->save();
    }
}
