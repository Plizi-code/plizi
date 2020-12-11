<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTriggerForUserProfileImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            create trigger update_profile_user_pic after insert on image_uploads
            for each row
            BEGIN
                IF new.tag = 'primary' THEN
                    UPDATE profiles SET user_pic = new.url WHERE user_id = new.user_id;
                END IF;
            END
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `update_profile_user_pic`');
    }
}

