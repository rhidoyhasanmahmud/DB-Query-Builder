<?php

use Illuminate\Database\Seeder;

class CourseTeachersTableSeeder extends Seeder
{
    public function run()
    {
        $teachers = [
            [
                'teacher_name' => 'Md. A',
                'department_id' => 1
            ],
            [
                'teacher_name' => 'Mrs. B',
                'department_id' => 2
            ],
            [
                'teacher_name' => 'Mr. C',
                'department_id' => 3
            ],
            [
                'teacher_name' => 'Md. D',
                'department_id' => 4
            ],
            [
                'teacher_name' => 'Mrs. E',
                'department_id' => 5
            ],
        ];

        foreach ($teachers as $teacher) {
            App\CourseTeacher::create($teacher);
        }
    }
}
