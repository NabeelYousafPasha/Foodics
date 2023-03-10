<?php

namespace Database\Seeders;

use App\Models\StandardUnit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StandardUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $SIUnits = [
            'kg' => [
                'name' => 'kilogram',
            ],
            'g' => [
                'name' => 'gram',
            ],
        ];

        foreach ($SIUnits as $SIUnitCode => $SIUnit) {
            StandardUnit::updateOrCreate([
                'code' => $SIUnitCode
            ], $SIUnit);
        }
    }
}
