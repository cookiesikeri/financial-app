<?php

namespace Database\Seeders;

use App\Models\MonthlyIncome;
use Illuminate\Database\Seeder;

class IncomeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(0,8) as $index){
            $exist = MonthlyIncome::on('mysql::read')->where([
                'min' => $this->incomes[$index]['min'],
                'max' => $this->incomes[$index]['max']
            ])->exists();

            if (!$exist) {
                MonthlyIncome::on('mysql::write')->create([
                    'range' => $this->incomes[$index]['range'],
                    'min' => $this->incomes[$index]['min'],
                    'max' => $this->incomes[$index]['max'],
                ]);
            }
        }
    }
    protected $incomes = [
        0 => [
            'range' => '0 - 5000',
            'min' => 0,
            'max' => 5000,
        ],
        1 => [
            'range' => '5001 - 15000',
            'min' => 5001,
            'max' => 15000,
        ],
        2 => [
            'range' => '15001 - 30000',
            'min' => 15001,
            'max' => 30000,
        ],
        3 => [
            'range' => '30001 - 50000',
            'min' => 30001,
            'max' => 50000,
        ],
        4 => [
            'range' => '50001 - 100000',
            'min' => 50001,
            'max' => 100000,
        ],
        5 => [
            'range' => '100001 - 200000',
            'min' => 100001,
            'max' => 200000,
        ],
        6 => [
            'range' => '200001 - 300000',
            'min' => 200001,
            'max' => 300000,
        ],
        7 => [
            'range' => '300001 - 500000',
            'min' => 300001,
            'max' => 500000,
        ],
        8 => [
            'range' => '500001 +',
            'min' => 500001,
            'max' => null,
        ],
    ];
}
