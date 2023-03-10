<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\StandardUnit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $SIgram = StandardUnit::ofCode('g')->first();

        $ingredients = [
            'beef' => [
                'base_quantity' => '20',
                'standard_unit_id' => $SIgram->id,
                'available_quantity' => '20',
                'threshold_level' => '50',
                'threshold_unit' => '%',
            ],
            'cheese' => [
                'base_quantity' => '5',
                'standard_unit_id' => $SIgram->id,
                'available_quantity' => '5',
                'threshold_level' => '50',
                'threshold_unit' => '%',
            ],
            'onion' => [
                'base_quantity' => '1',
                'standard_unit_id' => $SIgram->id,
                'available_quantity' => '1',
                'threshold_level' => '50',
                'threshold_unit' => '%',
            ],
        ];

        foreach ($ingredients as $ingredient => $ingredientAttributes) {
            Ingredient::updateOrCreate([
                'code' => $ingredient,
            ], $ingredientAttributes + ['name' => ucfirst($ingredient)]);
        }
    }
}
