<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use App\Models\ProductIngredient;
use App\Models\StandardUnit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $SIunits = StandardUnit::pluck('id', 'code');
        $ingredients = Ingredient::pluck('id', 'code');

        $products = [
            'burger' => [
                $ingredients['beef'] => [
                    'ingredient_quantity' => '150',
                    'standard_unit_id' => $SIunits['g'],
                ],
                $ingredients['cheese'] => [
                    'ingredient_quantity' => '30',
                    'standard_unit_id' => $SIunits['g'],
                ],
                $ingredients['onion'] => [
                    'ingredient_quantity' => '20',
                    'standard_unit_id' => $SIunits['g'],
                ],
            ],
        ];

        foreach ($products as $product => $productIngredients) {
            $product = Product::updateOrCreate([
                'code' => $product,
            ], [
                'name' => ucfirst($product),
            ]);

            foreach ($productIngredients as $productIngredient => $productIngredientAttributes) {
                ProductIngredient::updateOrCreate([
                    'product_id' => $product->id,
                    'ingredient_id' => $productIngredient,
                ], $productIngredientAttributes);
            }
        }
    }
}
