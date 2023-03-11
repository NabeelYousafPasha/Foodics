<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\OrderPlacedEvent;
use App\Exceptions\ProductQuantityExceedsIngredientStockException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Order\OrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    protected $orderService;

    /**
     * @param OrderService $orderService
     */
    public function __construct(
        OrderService $orderService
    )
    {
        $this->orderService = $orderService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OrderRequest $request
     * @return JsonResponse
     */
    public function store(OrderRequest $request): JsonResponse
    {
        try {
            $this->orderService->setOrder($request->input('products'));

            $this->orderService->handleIfProcessableOrder();


            // event(new OrderPlacedEvent());

            return $this->successResponse(
                message: 'Order Placed Successfully',
                data: [
                    'order' => Order::first(),
                ],
                responseCode: Response::HTTP_CREATED
            );

        } catch (ProductQuantityExceedsIngredientStockException $productQuantityExceedsIngredientStockException) {

            return $this->errorResponse(
                message: "Order could not be placed due to: {$productQuantityExceedsIngredientStockException->getMessage()}",
                responseCode: Response::HTTP_UNPROCESSABLE_ENTITY,
                data: [
                    'order' => Order::first(),
                ]
            );

        } catch (\Exception $exception) {

            return $this->errorResponse(
                message: "Order could not be placed due to: {$exception->getMessage()}",
                responseCode: Response::HTTP_INTERNAL_SERVER_ERROR,
                data: [
                    'error' => $exception->getMessage(),
                ]
            );

        }
    }
}
