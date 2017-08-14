<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('author_id');
//            $table->unsignedInteger('position_quest_id');
//            $table->text("source"); //исходные данные
            $table->text("task"); //задание
            $table->unsignedInteger('timer')->default(20); //Обратный отсчет
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('author_id')
                ->references('id')->on('users');
//            $table->foreign('position_id')
//                ->references('id')->on('positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quests');
    }
}
