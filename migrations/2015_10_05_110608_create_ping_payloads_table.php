<?php

use Illuminate\Database\Migrations\Migration;

class CreatePingPayloadsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('ping_payloads', function ($tbl) {
            $tbl->increments('id');
            $tbl->text('message');
            $tbl->boolean('seen')->default(0);
            $tbl->boolean('sender_deleted')->default(0);
            $tbl->boolean('receiver_deleted')->default(0);
            $tbl->integer('sender_id');
            $tbl->integer('connection_id');
            $tbl->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('ping_payloads');
    }
}
