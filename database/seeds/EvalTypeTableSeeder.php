<?php

use Illuminate\Database\Seeder;

class EvalTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $evalType = new \App\EvalType();
        $evalType->name = "90&deg;";
        $evalType->note = "";
        $evalType->save();

        $evalType = new \App\EvalType();
        $evalType->name = "180&deg;";
        $evalType->note = "";
        $evalType->save();

        $evalType = new \App\EvalType();
        $evalType->name = "270&deg;";
        $evalType->note = "";
        $evalType->save();

        $evalType = new \App\EvalType();
        $evalType->name = "360&deg;";
        $evalType->note = "";
        $evalType->save();
    }
}
