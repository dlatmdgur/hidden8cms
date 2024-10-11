<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('admin_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->string('menu');
            $table->string('action');
            $table->text('params');
            $table->string('reason');
            $table->string('extra');
            $table->integer('user_seq');
            $table->string('nickname');
            $table->integer('before_state');
            $table->integer('after_state');
            $table->integer('admin_id');
            $table->string('admin_name');
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
        Schema::connection('mysql')->dropIfExists('admin_logs');
    }
}
