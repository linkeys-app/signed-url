<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = config('links.tables.groups');

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('expiry')->nullable();
            $table->integer('click_limit')->nullable();
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
        $tableName = config('links.tables.groups');

        Schema::drop($tableName);
    }
}
