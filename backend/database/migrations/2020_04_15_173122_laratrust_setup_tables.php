<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Models\User;

class LaratrustSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        // Create table for storing roles
        Schema::create('rbac_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->smallInteger('flag', false, true)->default(0);
            $table->smallInteger('priority', false, true)->default(0);
            $table->timestamps();
        });

        // Create table for storing permissions
        Schema::create('rbac_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for associating roles to users and teams (Many To Many Polymorphic)
        Schema::create('rbac_role_user', function (Blueprint $table) {
            $table->unsignedInteger('role_id');
            $table->string('user_id', 24);
            $table->string('user_type');

            $table->foreign('role_id')->references('id')->on('rbac_roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'role_id', 'user_type']);
        });

        // Create table for associating permissions to users (Many To Many Polymorphic)
        Schema::create('rbac_permission_user', function (Blueprint $table) {
            $table->unsignedInteger('permission_id');
            $table->string('user_id', 24);
            $table->string('user_type');

            $table->foreign('permission_id')->references('id')->on('rbac_permissions')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'permission_id', 'user_type']);
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('rbac_permission_role', function (Blueprint $table) {
            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('role_id');

            $table->foreign('permission_id')->references('id')->on('rbac_permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('rbac_roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });

        $this->createBaseRoles();
    }

    private function createBaseRoles()
    {
        \DB::table('rbac_roles')->insert(
            [
                'name' => 'self',
                'display_name' => 'Self user',
                'description'  => 'Только я',
                'priority' => 1,
            ]
        );
        \DB::table('rbac_roles')->insert(
            [
                'name' => User::PERMISSION_ROLE_GUEST,
                'display_name' => 'Guest',
                'description'  => 'Все пользователи',
                'flag' => 1,
                'priority' => 2,
            ]
        );
        \DB::table('rbac_roles')->insert(
            [
                'name' => User::PERMISSION_ROLE_FRIEND,
                'display_name' => 'Friend',
                'description'  => 'Только друзья',
                'priority' => 4,
            ]
        );
        \DB::table('rbac_roles')->insert(
            [
                'name' => User::PERMISSION_ROLE_FOF,
                'display_name' => 'Friend of friend',
                'description'  => 'Только друзья друзей',
                'priority' => 3,
            ]
        );
        \DB::table('rbac_roles')->insert(
            [
                'name' => 'selectedFriend',
                'display_name' => 'Selected Friend',
                'description'  => 'Выбранные друзья',
                'priority' => 5,
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('rbac_permission_user');
        Schema::dropIfExists('rbac_permission_role');
        Schema::dropIfExists('rbac_permissions');
        Schema::dropIfExists('rbac_role_user');
        Schema::dropIfExists('rbac_roles');
    }
}
