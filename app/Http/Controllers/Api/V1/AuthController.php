<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->post('name'),
            'password' => bcrypt($request->post('password')),
            'email' => $request->post('email'),
        ]);

        return $this->successResponse(
            message: 'User Registered Successfully',
            data: [
                'token' => $user->createToken('API Token')->plainTextToken,
            ],
            responseCode: Response::HTTP_CREATED
        );
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email:rfc,dns',],
            'password' => ['required', 'string',],
        ]);

        if (! Auth::attempt($request->only(['email', 'password']))) {

            return $this->errorResponse(
                message: 'Credentials does not match',
                responseCode: 401,
            );
        }

        return $this->successResponse(
            message: 'User Authenticated Successfully',
            data: [
                'token' => auth()->user()->createToken('API Token')->plainTextToken,
            ],
            responseCode: Response::HTTP_CREATED
        );
    }

}
