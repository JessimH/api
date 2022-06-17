<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('profile_picture')->nullable();
            $table->integer('note')->nullable();
            $table->json('sports');
            $table->integer('followers')->nullable();
            $table->integer('following')->nullable();
            $table->string('comments')->nullable();
            $table->integer('sessions')->nullable();
            $table->integer('messages')->nullable();
            $table->string('subsciptions')->nullable();
            $table->boolean('is_pro')->default(0);
            $table->integer('balance')->nullable();
            $table->string('subscribers')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
