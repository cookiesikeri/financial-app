<?php

namespace Database\Seeders;

use App\Models\EmploymentStatus;
use Illuminate\Database\Seeder;

class EmploymentStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->statuses as $status) {
            $exist = EmploymentStatus::on('mysql::read')->where('status', $status)->exists();
            if (!$exist) {
                EmploymentStatus::on('mysql::write')->create([
                    'status' => $status,
                ]);
            }
        }
    }
    protected $statuses = [
        'Government Employee',
        'Private Employee',
        'Self Employee',
        'Student',
    ];
}
