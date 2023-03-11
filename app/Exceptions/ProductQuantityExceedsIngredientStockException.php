<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProductQuantityExceedsIngredientStockException extends Exception
{
    use ApiResponse;

    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report()
    {
        Log::debug('Quantity of Product(s) exceeds currently available ingredients');
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     */
    public function render(Request $request): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
    {
        if ($request->wantsJson()) {
            return $this->errorResponse(
                message: 'Quantity of Product(s) exceeds currently available ingredients',
                responseCode: Response::HTTP_UNPROCESSABLE_ENTITY,
            );
        }

        return response('Quantity of Product(s) exceeds currently available ingredients');
    }
}
