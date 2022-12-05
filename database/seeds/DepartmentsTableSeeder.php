<?php

use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{

    public function run()
    {
        $departments = [
            [
                'dpt_name' => 'CSE',
            ],
            [
                'dpt_name' => 'EEE',
            ],
            [
                'dpt_name' => 'CE',
            ],
            [
                'dpt_name' => 'Pharmacy',
            ],
            [
                'dpt_name' => 'BBA',
            ],
        ];

        foreach ($departments as $department) {
            App\Department::create($department);
        }
    }
}
