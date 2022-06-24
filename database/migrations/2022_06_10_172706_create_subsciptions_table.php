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
        Schema::create('subsciptions', function (Blueprint $table) {
            $table->id();
            $table->integer('price');
            $table->integer('user_id');
            $table->integer('pro_id');
            $table->timestamps();
            $table->date('start_subscription');
            $table->date('end_subscription');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subsciptions');
    }
};
