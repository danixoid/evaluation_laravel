<?php

use Illuminate\Database\Seeder;

class EvalRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new \App\EvalRole();
        $role->name = "Самооценка";
        $role->code = "self";
        $role->title = "Оцениваемый сотрудник оценивает самого себя";
        $role->save();

        $role = new \App\EvalRole();
        $role->name = "Оценка руководителя";
        $role->code = "chief";
        $role->title = "Непосредственный руководитель делает оценку";
        $role->save();

        $role = new \App\EvalRole();
        $role->name = "Оценка подчиненного";
        $role->code = "under";
        $role->title = "При оценке работника с руководительной должностью оценка подчиненным";
        $role->save();

        $role = new \App\EvalRole();
        $role->name = "Оценка коллеги";
        $role->code = "coworker";
        $role->title = "Оценка сотрудника равного по структуре";
        $role->save();

        $role = new \App\EvalRole();
        $role->name = "Оценка клиентом";
        $role->code = "client";
        $role->title = "Оценка клиентом, работающий с оцениваемым сотрудником";
        $role->save();

        $role = new \App\EvalRole();
        $role->name = "Итоговая оценка";
        $role->code = "total";
        $role->title = "Итоговая оценка ставится высшим руководителем";
        $role->save();

    }
}
