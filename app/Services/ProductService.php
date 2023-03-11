<?php

namespace App\Services;

use App\Models\Ingredient;
use Illuminate\Support\Facades\Log;

class ProductService
{
    private $productIndex;

    private $product;

    private $quantity;

    protected $ingredientService;

    /**
     * @param IngredientService $ingredientService
     */
    public function __construct(
        IngredientService $ingredientService,
    )
    {
        $this->ingredientService = $ingredientService;
    }

    /**
     * @return mixed
     */
    public function getProductIndex()
    {
        return $this->productIndex;
    }

    /**
     * @param mixed $productIndex
     */
    public function setProductIndex($productIndex): void
    {
        $this->productIndex = $productIndex;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product): void
    {
        $this->product = $product;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
    }

    public function handleAvailabilityOfProductQuantity()
    {
        $productIngredients = Ingredient::query()
            ->addSelect([
                'ingredients.*',

                'products.id as p_id',
                'products.code as p_code',

                'product_ingredients.product_id as pi_product_id',
                'product_ingredients.ingredient_quantity as pi_ingredient_quantity',
                'product_ingredients.standard_unit_id as pi_standard_unit_id',
            ])
            ->porductIngredients($this->getProduct()->id)
            ->get();

        Log::info("details for {$this->getQuantity()} - {$this->getProduct()->code}(s)");

        $this->ingredientService->setProductIngredients($productIngredients);
        $this->ingredientService->setProductQuantity($this->getQuantity());

        $this->ingredientService->calculate();
    }
}
