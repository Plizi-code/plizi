<?php

use Illuminate\Database\Migrations\Migration;

class AddRoleToMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE community_members MODIFY COLUMN role ENUM('author', 'admin', 'user', 'guest')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE community_members MODIFY COLUMN role ENUM('author', 'admin', 'user')");
    }
}
