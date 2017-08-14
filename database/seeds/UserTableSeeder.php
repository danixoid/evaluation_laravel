<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new User();
        $admin->name = "Администратор";
        $admin->email = "danixoid@gmail.com";
        $admin->iin = "123456789012";
        $admin->password = bcrypt("12345");
        $admin->save();

        $role_admin  = Role::where("name", "admin")->first();
        $admin->roles()->attach($role_admin);

        $manager = new User();
        $manager->name = "Менеджер";
        $manager->email = "manager@example.com";
        $manager->iin = "123456789013";
        $manager->password = bcrypt("12345");
        $manager->save();

        $role_manager  = Role::where("name", "manager")->first();
        $manager->roles()->attach($role_manager);

/*
        $role_employee = Role::where("name", "employee")->first();
        for($i = 0; $i < 20; $i++) :
            $employee = new User();
            $employee->name = "Иван" . $i . " Иванов" . $i;
            $employee->email = "employee" . $i . "@example.com";
            $employee->iin = "8703074011" . ($i < 10 ? "0" : "") . $i;
            $employee->password = bcrypt("12345");
            $employee->save();

            $employee->roles()->attach($role_employee);
        endfor;*/
    }
}
