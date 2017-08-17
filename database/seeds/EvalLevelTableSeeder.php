<?php

use Illuminate\Database\Seeder;

class EvalLevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $eval_level = new \App\EvalLevel();
        $eval_level->level = 1;
        $eval_level->min = 0;
        $eval_level->max = 2.7;
        $eval_level->name = "1 уровень (осведомленность)";
        $eval_level->note = "компетенция  продемонстрирована в отдельных ситуациях; требуется целенаправленное развитие начального уровня компетенции";
        $eval_level->save();

        $eval_level = new \App\EvalLevel();
        $eval_level->level = 2;
        $eval_level->min = 2.7;
        $eval_level->max = 3.4;
        $eval_level->name = "2 уровень (направленность на результат)";
        $eval_level->note = "компетенция продемонстрирована в 50% ситуаций на требуемом уровне; требует серьезных улучшений (область целенаправленного развития компетенции)";
        $eval_level->save();

        $eval_level = new \App\EvalLevel();
        $eval_level->level = 3;
        $eval_level->min = 3.4;
        $eval_level->max = 4.1;
        $eval_level->name = "3 уровень (опытное применение)";
        $eval_level->note = "компетенция продемонстрирована полностью на соответствующем уровне; требует улучшений (область целенаправленного развития смежных компетенций)";
        $eval_level->save();

        $eval_level = new \App\EvalLevel();
        $eval_level->level = 4;
        $eval_level->min = 4.1;
        $eval_level->max = 4.4;
        $eval_level->name = "4 уровень (совершенное владение)";
        $eval_level->note = "компетенция частично продемонстрирована на уровне несколько выше требуемого; требует небольших улучшений (область развития сильных сторон компетенций)";
        $eval_level->save();

        $eval_level = new \App\EvalLevel();
        $eval_level->level = 5;
        $eval_level->min = 4.4;
        $eval_level->max = 5;
        $eval_level->name = "5 уровень (эксперт)";
        $eval_level->note = "компетенция продемонстрирована на уровне выше требуемого; конкурентное преимущество большинства компетенций";
        $eval_level->save();
    }
}
