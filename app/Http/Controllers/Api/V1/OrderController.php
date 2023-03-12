<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\OrderPlacedEvent;
use App\Exceptions\IngredientOutOfStockException;
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

            $orderDetails = $this->orderService->handleIfProcessableOrder();

            $order = $this->orderService->createPendingOrder($orderDetails, $request->user());

             event(new OrderPlacedEvent($order));

            return $this->successResponse(
                message: 'Order Placed Successfully',
                data: [
                    'order' => $order,
                ],
                responseCode: Response::HTTP_CREATED
            );

        } catch (ProductQuantityExceedsIngredientStockException $productQuantityExceedsIngredientStockException) {

            return $this->errorResponse(
                message: "Product Quantity Exceeded Stock: {$productQuantityExceedsIngredientStockException->getMessage()}",
                responseCode: Response::HTTP_UNPROCESSABLE_ENTITY,
                data: [
                    $request->all(),
                ]
            );
        } catch (IngredientOutOfStockException $ingredientOutOfStockException) {

            return $this->errorResponse(
                message: "Ingredients Out of Stock: {$ingredientOutOfStockException->getMessage()}",
                responseCode: Response::HTTP_UNPROCESSABLE_ENTITY,
                data: [
                    $request->all(),
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
