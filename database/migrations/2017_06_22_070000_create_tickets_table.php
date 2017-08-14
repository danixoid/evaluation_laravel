<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('quest_id');
            $table->unsignedInteger('exam_id');
            $table->text('answer')->nullable(); //решение задания
            $table->timestamp('started_at')->nullable(); //время завершения
            $table->timestamp('finished_at')->nullable(); //время завершения
            $table->unsignedTinyInteger('mark')->default(0); //оценка за задание
            $table->text('note')->nullable(); //комментарий руководителя
            $table->timestamps();

            $table->foreign('quest_id')
                ->references('id')->on('quests');
            $table->foreign('exam_id')
                ->references('id')->on('exams');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
