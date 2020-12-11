<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilePrivacySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_privacy_settings', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('user_id', 24);
            $table->smallInteger('page_type', false, true)->default(0);
            $table->smallInteger('write_messages_permissions', false, true)->default(0);
            $table->smallInteger('post_wall_permissions', false, true)->default(0);
            $table->smallInteger('view_wall_permissions', false, true)->default(0);
            $table->smallInteger('view_friends_permissions', false, true)->default(0);
            $table->smallInteger('two_factor_auth_enabled', false, true)->default(0);
            $table->smallInteger('sms_confirm', false, true)->default(0);
            $table->integer('created_at', false, true);
            $table->integer('updated_at', false, true);
        });
    }

    /**2020_04_08_160920_create_users_blacklisted_table
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_privacy_settings');
    }
}
