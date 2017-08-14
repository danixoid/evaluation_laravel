<?php

use Illuminate\Database\Seeder;

class QuestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\User::first();

        for($i = 0; $i < 100; $i++):
            $quest = new \App\Quest();
//                $quest->position_id = $position->id;
            $quest->author_id = $user->id;
//                $quest->source = "Исходные денные №$i для $position->orgPath $position->name, составитель $user->name";
            $quest->task = "Исходные денные №$i, составитель $user->name
                    Задание №$i, составитель $user->name";
            $quest->timer = 15;

            $quest->save();

            foreach(\App\Position::inRandomOrder()
                        ->take(rand(1,5))
                        ->get()
                    as $position) :

                $org = \App\Org::inRandomOrder()->first();
                $func = \App\Func::inRandomOrder()->first();

                $quest
                    ->positions()
                    ->attach($position,["org_id" => $org->id, "func_id" => $func->id ]);
            endforeach;
        endfor;

    }
}
