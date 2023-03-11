<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Log;

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

    public function handleIfProcessableOrder()
    {
        foreach ($this->getOrder() as $productKey => $product) {

            $this->productService->setProductIndex($productKey);

            Log::info("checking for Product at Position $productKey");
            Log::info(json_encode($product));

            $this->productService->setProduct(Product::find($product['product_id']));
            $this->productService->setQuantity($product['quantity']);

            $this->productService->handleAvailabilityOfProductQuantity();

        }
    }
}
