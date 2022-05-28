<?php

namespace Database\Seeders;

use App\Models\EducationalQualification;
use Illuminate\Database\Seeder;

class QualificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->qualifications as $qualification) {
            $is_exist = EducationalQualification::on('mysql::read')->where('name', $qualification)->exists();
            if(!$is_exist) {
                EducationalQualification::on('mysql::write')->create([
                    'name' => $qualification,
                ]);
            }
        }
    }
    protected $qualifications = [
        'First School Leaving Certificate',
        'Senior School Certificate',
        'National Diploma',
        'Grade II Teachers’ Certificate',
        'National Certificate in Education',
        'Higher National Diploma',
        'Bachelor\'s Degree',
        'Bachelor Honours Degree',
        'Doctor of Veterinary Medicine',
        'Postgraduate Diploma',
        'Master\'s Degree',
        'Master of Philosophy',
        'Doctor of Philosophy',
    ];
}
