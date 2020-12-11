<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('notifications');
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->string('notifiable_id', 24);
            $table->string('notifiable_type');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
        Schema::create('notifications', function (Blueprint $table) {
            $table->integer('id', true)->primary();
            $table->string('sender_id');
            $table->string('recipient_id');
            $table->string('action');
            $table->boolean('is_read')->default(false);
            $table->integer('created_at')->default(time());
            $table->integer('updated_at')->default(time());
        });
    }
}
