<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $students = [
            [
                'name' => 'Abul',
                'email' => 'abul@cse.com',
                'department_id' => 1,
                'course_teacher_id' => 1,
                'status' => 1,
                'total_credit' => 150
            ],
            [
                'name' => 'Babul',
                'email' => 'babul@eee.com',
                'department_id' => 2,
                'course_teacher_id' => 2,
                'status' => 1,
                'total_credit' => 160
            ],
            [
                'name' => 'Kabul',
                'email' => 'kabul@ce.com',
                'department_id' => 3,
                'course_teacher_id' => 3,
                'status' => 1,
                'total_credit' => 140
            ],
            [
                'name' => 'Habul',
                'email' => 'habul@pharmacy.com',
                'department_id' => 4,
                'course_teacher_id' => 4,
                'status' => 1,
                'total_credit' => 130
            ],
            [
                'name' => 'Bulbul',
                'email' => 'bulbul@bba.com',
                'department_id' => 5,
                'course_teacher_id' => 5,
                'status' => 1,
                'total_credit' => 130
            ],
        ];

        foreach ($students as $student) {
            App\User::create($student);
        }
    }
}
