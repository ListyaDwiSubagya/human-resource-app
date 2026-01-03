<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class HumanResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        DB::table('departments')->insert([
            [
                'name' => 'HR',
                'description' => 'Human Resources',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'IT',
                'description' => 'Department Information Tech',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Sales',
                'description' => 'Department Sales',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);

        DB::table('roles')->insert([
            ['title' => 'Manager', 'description' => 'Handles team management', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['title' => 'Developer', 'description' => 'Handles software development', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['title' => 'Salesperson', 'description' => 'Handles sales and client communication', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
        DB::table('employees')->insert([
            [
                'fullname' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone_number' => $faker->phoneNumber,
                'address' => $faker->address,
                'birth_date' => $faker->dateTimeBetween('-40 years', '-20 years'),
                'hire_date' => Carbon::now(),
                'department_id' => 1,  // HR
                'role_id' => 1,        // Manager
                'status' => 'active',
                'salary' => $faker->randomFloat(2, 3000, 6000),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
            [
                'fullname' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone_number' => $faker->phoneNumber,
                'address' => $faker->address,
                'birth_date' => $faker->dateTimeBetween('-35 years', '-25 years'),
                'hire_date' => Carbon::now(),
                'department_id' => 2,  // IT
                'role_id' => 2,        // Developer 
                'status' => 'active',
                'salary' => $faker->randomFloat(2, 4000, 7000),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
        ]);

        // Seed Tasks table
        DB::table('tasks')->insert([
            [
                'title' => $faker->sentence(3),
                'description' => $faker->paragraph,
                'assigned_to' => 1, // John Doe
                'due_date' => Carbon::parse('2025-02-15'),
                'status' => 'pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => $faker->sentence(3),
                'description' => $faker->paragraph,
                'assigned_to' => 2, // Jane Smith
                'due_date' => Carbon::parse('2025-02-20'),
                'status' => 'in-progress',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Seed Payroll table
        DB::table('payrolls')->insert([
            [
                'employee_id' => 1, // John Doe
                'salary' => $faker->randomFloat(2, 4000, 6000),
                'bonuses' => $faker->randomFloat(2, 100, 500),
                'deductions' => $faker->randomFloat(2, 50, 200),
                'net_salary' => $faker->randomFloat(2, 4000, 6000),
                'pay_date' => Carbon::parse('2025-02-01'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'employee_id' => 2, // Jane Smith
                'salary' => $faker->randomFloat(2, 4000, 7000),
                'bonuses' => $faker->randomFloat(2, 100, 500),
                'deductions' => $faker->randomFloat(2, 50, 200),
                'net_salary' => $faker->randomFloat(2, 4000, 7000),
                'pay_date' => Carbon::parse('2025-02-01'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Seed Attendance table
        DB::table('presences')->insert([
            [
                'employee_id' => 1, // John Doe
                'check_in' => Carbon::parse('2025-02-10 09:00:00'),
                'check_out' => Carbon::parse('2025-02-10 17:00:00'),
                'date' => Carbon::parse('2025-02-10'),
                'status' => 'present',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'employee_id' => 2, // Jane Smith
                'check_in' => Carbon::parse('2025-02-10 09:15:00'),
                'check_out' => Carbon::parse('2025-02-10 17:00:00'),
                'date' => Carbon::parse('2025-02-10'),
                'status' => 'present',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Seed Leave Requests table
        DB::table('leave_requests')->insert([
            [
                'employee_id' => 1, // John Doe
                'leave_type' => 'Sick Leave',
                'start_date' => Carbon::parse('2025-02-12'),
                'end_date' => Carbon::parse('2025-02-12'),
                'status' => 'approved',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'employee_id' => 2, // Jane Smith
                'leave_type' => 'Vacation',
                'start_date' => Carbon::parse('2025-02-15'),
                'end_date' => Carbon::parse('2025-02-18'),
                'status' => 'pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
