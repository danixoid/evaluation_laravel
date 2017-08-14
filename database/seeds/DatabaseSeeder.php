<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Role comes before User seeder here.
        $this->call(RoleTableSeeder::class);
        // User seeder will use the roles above created.
        $this->call(UserTableSeeder::class);
        // Orgs seeder will created.
        $this->call(OrgTableSeeder::class);
        // Functs seeder will created.
        $this->call(FuncTableSeeder::class);
        // Positions seeder will created.
        $this->call(PositionTableSeeder::class);
        // Quests seeder will created.
//        $this->call(QuestTableSeeder::class);
        // Exam seeder will created.
//        $this->call(ExamTableSeeder::class);
    }
}
