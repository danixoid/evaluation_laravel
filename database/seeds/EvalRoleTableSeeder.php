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
        $role->title = "Оцениваемый сотрудник оценивает самого себя";
        $role->save();

        $role = new \App\EvalRole();
        $role->name = "Оценка руководителя";
        $role->title = "Непосредственный руководитель делает оценку";
        $role->save();

        $role = new \App\EvalRole();
        $role->name = "Оценка подчиненного";
        $role->title = "При оценке работника с руководительной должностью оценка подчиненным";
        $role->save();

        $role = new \App\EvalRole();
        $role->name = "Оценка коллеги";
        $role->title = "Оценка сотрудника равного по структуре";
        $role->save();

        $role = new \App\EvalRole();
        $role->name = "Оценка клиентом";
        $role->title = "Оценка клиентом, работающий с оцениваемым сотрудником";
        $role->save();

        $role = new \App\EvalRole();
        $role->name = "Итоговая оценка";
        $role->title = "Итоговая оценка ставится высшим руководителем";
        $role->save();

    }
}
