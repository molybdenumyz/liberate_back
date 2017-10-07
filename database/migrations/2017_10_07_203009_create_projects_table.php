<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('title',100);
            $table->bigInteger('start_at');
            $table->bigInteger('end_at');
            $table->string('description');
            $table->integer('type');
            $table->tinyInteger('is_public');
            $table->string('password')->nullable();
            $table->integer('max_choose');
            $table->tinyInteger('has_pic');
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
        //
    }
}
