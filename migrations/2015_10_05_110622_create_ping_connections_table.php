<?php

use Illuminate\Database\Migrations\Migration;

class CreatePingConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('ping_connections', function ($table) {
            $table->increments('id');
            $table->integer('user_one');
            $table->integer('user_two');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('ping_connections');
    }
}
