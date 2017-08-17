<?php

use Illuminate\Database\Seeder;

class CompetenceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $corp = [
            ["Лидерство","Лидерство - намерение выполнять роль лидера команды или иных групп людей. Подразумевает стремление руководить остальными. Лидерство в команде обычно, но не всегда, выражается в виде официальных полномочий"],
            ["Развитие окружающих людей","Искреннее намерение способствовать развитию окружающих людей"],
            ["Бизнес-планирование","Способность активно и быстро прогнозировать (деловые) возможности/риски, основываясь на хорошем понимании контекста/рынка, и разработать плана соответственно. Это подразумевает способность ухватывать и развивать существующие и потенциальные возможности и/или угрозы, заранее влияя на события, а не просто реагируя на них"],
            ["Воздействие и влияние","Способность убедить других (человека или группу людей) разделить и поддержать его идеи. Данная компетенция основывается на желании произвести определенное воздействие на остальных и желании передать впечатление определенного рода или особенной модальности, которую стремится передать влияющий человек"],
            ["Сотрудничество","Сотрудничество предполагает истинное намерение работать совместно с другими, быть частью команды и работать вместе в отличии от индивидуальной работы или на конкурентной основе"],
            ["Ориентированность на клиента","Ориентированность на клиента подразумевает стремление оказать помощь/услугу другим. Означает сосредоточение усилий на определении и удовлетворении потребностей клиентов"],
            ["Нацеленность на результат","Достижение нацеленности на результат является проблемой хорошего выполнения работы или конкуренции со стандартом качественного выполнения работы. Стандартом может быть уровень собственной эффективности деятельности (стремление к улучшению), объективная мера (нацеленность на результаты), уровень эффективности деятельности других (конкурентоспособность), вызовы, поставленные работником перед собой, или даже то, чего никто никогда не делал (инновация)"],
            ["Качество выполнения работ","Стремление максимизировать соответствие результатов своей работы с заданными стандартами. Отражает основной фактор в целях уменьшения неопределенности и совершенствования порядка в окружающей среду"],
            ["Приверженность ценностям Компании","действовать согласно с убеждениями человека, вести себя в соответствии с ценностями, которые могут исходить от компании, общества, а также (положительного) этического или персонального кодекса"],
            ["Саморазвитие","Желание расти профессионально, адаптируясь к изменениям в рабочей среде, включая проактивность в поиске возможностей для личного обучения и роста, и бросание вызовa своим собственным методам работы"],
        ];


        // ТИПЫ КОМПЕТЕНЦИИ

        $typeCorp = new \App\CompetenceType();
        $typeCorp->name = "Корпоративные";
        $typeCorp->note = "Корпоративные компетенции";
        $typeCorp->save();

//        $typeMan = new \App\CompetenceType();
//        $typeMan->name = "Управленческие";
//        $typeMan->note = "Управленческие компетенции";
//        $typeMan->chief = true;
//        $typeMan->save();

        $typeProf = new \App\CompetenceType();
        $typeProf->name = "Профессиональные";
        $typeProf->note = "Профессиональные компетенции";
        $typeProf->prof = true;
        $typeProf->save();

        foreach ($corp as $el)
        {
            $comp = new \App\Competence();
            $comp->competence_type_id = $typeCorp->id;
            $comp->name = $el[0];
            $comp->note = $el[1];
            $comp->save();
        }

    }
}