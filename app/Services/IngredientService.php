<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class IngredientService
{
    private $ingredient;

    protected $productIngredients;

    protected $productQuantity;

    /**
     * @return mixed
     */
    public function getIngredient()
    {
        return $this->ingredient;
    }

    /**
     * @param mixed $ingredient
     */
    public function setIngredient($ingredient): void
    {
        $this->ingredient = $ingredient;
    }

    /**
     * @return mixed
     */
    public function getProductIngredients()
    {
        return $this->productIngredients;
    }

    /**
     * @param mixed $productIngredients
     */
    public function setProductIngredients($productIngredients): void
    {
        $this->productIngredients = $productIngredients;
    }

    /**
     * @return mixed
     */
    public function getProductQuantity()
    {
        return $this->productQuantity;
    }

    /**
     * @param mixed $productQuantity
     */
    public function setProductQuantity($productQuantity): void
    {
        $this->productQuantity = $productQuantity;
    }


    public function calculate()
    {
        foreach ($this->getProductIngredients() as $productIngredient) {

            $this->setIngredient($productIngredient);

            $this->calculateIngredientUnit();
        }
    }

    // @IMPORTANT for this I'm using hardcoded values,
    // otherwise we could use Standard International Unit "conversions dynamically"
    public function calculateIngredientUnit()
    {
        $ingredientBaseUnit = 'kg';
        $productIngredientUnit = 'g';

        $ingredient = $this->getIngredient();

        Log::info("$ingredient->code - available quantity = $ingredient->available_quantity $ingredientBaseUnit");

        $productIngredientQuantity = $ingredient->pi_ingredient_quantity * $this->getProductQuantity();
        Log::info("$ingredient->code - consumed will be = $productIngredientQuantity $productIngredientUnit");

        $ingredientBalanceQuantity = $ingredient->available_quantity - ($productIngredientQuantity / 1000);
        Log::info("$ingredient->code - balance quantity = $ingredientBalanceQuantity $ingredientBaseUnit");

        $ingredient->available_quantity = $ingredientBalanceQuantity;

        Log::info("========= check threshold and if already notified =========");

        $ingredientThresholdCalculatedValue = $ingredient->calculateThresholdValue();
        Log::info("$ingredient->code - calculated threshold value = $ingredientThresholdCalculatedValue $ingredientBaseUnit");

        if ($ingredient->hasIngredientThresholdLevelAchieved()
            &&
            ! $ingredient->hasThresholdAlreadyNotified()
        ) {
            Log::info("$ingredient->code - !! DANGER !! alert we will notify as threshold value is achieved");


        }
    }
}
