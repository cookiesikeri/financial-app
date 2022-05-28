<?php

namespace Database\Seeders;

use App\Models\IdCardType;
use Illuminate\Database\Seeder;

class IdCardTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->types as $index => $type) {
            IdCardType::updateOrCreate([
                'id' => $index
            ], [
                'name' => $type
            ]);
        }
    }
    /**
     * @var array
     */
    protected $types = [
        1 => 'National Identity Number (NIN)',
        2 => 'International Passport',
        3 => 'Drivers License',
        4 => 'Voters Card'
    ];
}
