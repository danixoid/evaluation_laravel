<?php

use Illuminate\Database\Seeder;

class PositionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $poses = [
            'deps' => [
                'Директор',
                'Заместитель директора',
            ],
            'funct' => [
                'Руководитель группы',
                'Главный менеджер',
                'Главный менеджер 1 уровня',
                'Главный менеджер 2 уровня',
                'Главный менеджер 3 уровня',
                'Менеджер',
                'Менеджер 1 уровня',
                'Менеджер 2 уровня',
                'Менеджер 3 уровня',
                'Начальник управления',
                'Начальник отдела',
                'Руководитель',
                'Главный специалист',
                'Эксперт',
            ],
        ];

        foreach ($poses['deps'] as $pos) {
            $position = new \App\Position();
            $position->name = $pos;
            $position->save();
        }

        foreach ($poses['funct'] as $post) {
            $position = new \App\Position();
            $position->name = $post;
            $position->save();
        }
    }
}
