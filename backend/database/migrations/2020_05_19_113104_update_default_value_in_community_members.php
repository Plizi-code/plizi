<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDefaultValueInCommunityMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('community_members', function (Blueprint $table) {
            DB::statement('ALTER TABLE community_members MODIFY created_at INTEGER DEFAULT NULL ');
            DB::statement('ALTER TABLE community_members MODIFY updated_at INTEGER DEFAULT NULL ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('community_members', function (Blueprint $table) {
            //
        });
    }
}
