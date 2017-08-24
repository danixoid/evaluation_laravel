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
        $self = \App\EvalRole::whereCode('self')->first();
        $chief = \App\EvalRole::whereCode('chief')->first();
        $under = \App\EvalRole::whereCode('under')->first();
        $coworker = \App\EvalRole::whereCode('coworker')->first();
        $client = \App\EvalRole::whereCode('client')->first();
        $total = \App\EvalRole::whereCode('total')->first();

        $evalType = new \App\EvalType();
        $evalType->name = "90&deg;";
        $evalType->note = "Оценка ".$evalType->name;
        $evalType->save();
        $evalType->roles()->attach($chief,['max' => 1]);
        $evalType->roles()->attach($total,['max' => 1]);

        $evalType = new \App\EvalType();
        $evalType->name = "180&deg;";
        $evalType->note = "Оценка ".$evalType->name;
        $evalType->save();
        $evalType->roles()->attach($chief,['max' => 1]);
        $evalType->roles()->attach($self,['max' => 1]);
        $evalType->roles()->attach($total,['max' => 1]);

        $evalType = new \App\EvalType();
        $evalType->name = "270&deg;";
        $evalType->note = "Оценка ".$evalType->name;
        $evalType->save();
        $evalType->roles()->attach($chief,['max' => 1]);
        $evalType->roles()->attach($self,['max' => 1]);
        $evalType->roles()->attach($under,['max' => 5]);
        $evalType->roles()->attach($total,['max' => 1]);

        $evalType = new \App\EvalType();
        $evalType->name = "360&deg;";
        $evalType->note = "Оценка ".$evalType->name;
        $evalType->save();
        $evalType->roles()->attach($chief,['max' => 1 ]);
        $evalType->roles()->attach($self,['max' => 1 ]);
        $evalType->roles()->attach($under,['max' => 5 ]);
        $evalType->roles()->attach($coworker,['max' => 5 ]);
        $evalType->roles()->attach($client,['max' => 5 ]);
        $evalType->roles()->attach($total,['max' => 1]);
    }
}
