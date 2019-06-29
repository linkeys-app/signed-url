<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = config('links.tables.links');

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('url');
            $table->text('data');
            $table->dateTime('expiry')->nullable();
            $table->integer('click_limit')->nullable();
            $table->integer('clicks')->default(0);
            $table->uuid('uuid');
            $table->unsignedInteger('group_id')->nullable();
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
        $tableName = config('links.tables.links');

        Schema::drop($tableName);
    }
}
