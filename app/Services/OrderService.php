<?php

namespace App\Services;

use App\Events\IngredientThresholdEvent;
use App\Exceptions\IngredientOutOfStockException;
use App\Exceptions\ProductQuantityExceedsIngredientStockException;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderStatus;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OrderService
{
    protected $order;

    protected $productService;

    /**
     * @param ProductService $productService
     */
    public function __construct(
        ProductService $productService,
    )
    {
        $this->productService = $productService;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order): void
    {
        $this->order = $order;
    }

    /**
     * @return array
     * @throws ProductQuantityExceedsIngredientStockException
     */
    public function handleIfProcessableOrder()
    {
        $orderDetails = [];

        foreach ($this->getOrder() as $productKey => $product) {

            try {

                $this->productService->setProductIndex($productKey);

                Log::info("checking for Product at Position $productKey");
                Log::info(json_encode($product));

                $this->productService->setProduct(Product::find($product['product_id']));
                $this->productService->setQuantity($product['quantity']);

                $orderProductIngredientDetails = $this->productService->handleAvailabilityOfProductQuantity();


                $orderDetails[$productKey] = [
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'ingredient_details' => $orderProductIngredientDetails,
                ];

            } catch (IngredientOutOfStockException $ingredientOutOfStockException) {

                throw new ProductQuantityExceedsIngredientStockException("Quantity of Product at position $productKey can not be entertained, because {$ingredientOutOfStockException->getMessage()}");
            }
        }

        return $orderDetails;
    }

    public function createPendingOrder($orderDetails, $customer)
    {
        $order = Order::create([
            'user_id' => $customer->id,
            'order_status_id' => OrderStatus::ofCode(OrderStatus::PENDING)->first()->id,
            'dispatched_at' => null,
        ]);

        foreach ($orderDetails as $payloadIndex => $detail) {

            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $detail['product_id'],
                'quantity' => $detail['quantity'],
            ]);

            $this->updateIngredientStocks($detail['ingredient_details']);
        }
    }

    /**
     * @param $orderIgredientDetails
     */
    public function updateIngredientStocks($orderIgredientDetails)
    {
        foreach ($orderIgredientDetails as $ingredient) {
            $ingredient = Ingredient::find($ingredient['ingredient_id'])->first();

            $ingredient->update([
                'available_quantity' => $ingredient['balance_available_quantity'],
            ]);

            if ($ingredient['has_threshold_achieved'] ?? false) {
                event(new IngredientThresholdEvent($ingredient));
            }
        }
    }



}
