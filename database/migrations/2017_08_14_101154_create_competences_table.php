<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompetencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competences', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('competence_id');
            $table->string('name');
            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('competence_id')
                ->references('id')
                ->on('competences');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competences');
    }
}
