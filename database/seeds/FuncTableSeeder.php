<?php

use Illuminate\Database\Seeder;

class FuncTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $functs = [
            'ГРУППА ПО УПРАВЛЕНИЮ ЭКОНОМИЧЕСКОЙ И ФИНАНСОВОЙ ПОЛИТИКОЙ',
            'ГРУППА ПО ПЛАНИРОВАНИЮ КОНСОЛИДАЦИИ, АВТОМАТИЗАЦИИ И МОНИТОРИНГУ ПЛАНА РАЗВИТИЯ',
            'ГРУППА ПО ПЛАНИРОВАНИЮ КОНСОЛИДАЦИИ, АВТОМАТИЗАЦИИ И МОНИТОРИНГУ ПЛАНА РАЗВИТИЯ',
            'ГРУППА ПО УПРАВЛЕНИЮ ЭКОНОМИЧЕСКОЙ И ФИНАНСОВОЙ ПОЛИТИКОЙ ДОЧЕРНИХ ОРГАНИЗАЦИЙ',
        ];

        foreach ($functs as $funct)
        {
            $func = new \App\Func();
            $func->name = $funct;
            $func->save();
        }
    }
}
