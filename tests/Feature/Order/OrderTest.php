<?php

namespace Tests\Feature\Order;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use DatabaseTransactions;

    const EP = '/api/v1/orders';

    protected $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ];

    protected $payload = [
        'products' => [
            [
                'product_id' => 1,
                'quantity' => 2,
            ],
        ],
    ];

    /**
     * A guest / unauthorized can not place order test.
     *
     * @return void
     */
    public function test_order_must_not_be_placed_as_a_guest()
    {
        $response = $this->post(self::EP, [], $this->headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * authenticated user can post orders EP
     *
     * @return void
     */
    public function test_auth_user_can_place_order()
    {
        $user = $this->createUserAndGenerateAccessToken();

        $response = $this->actingAs($user)
            ->json('POST',
            self::EP,
                $this->payload,
                $this->headers
            );

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * product in payload must be validated
     *
     * @return void
     */
    public function test_order_must_contain_a_valid_product()
    {
        $user = $this->createUserAndGenerateAccessToken();

        $response = $this->actingAs($user)
            ->json('POST',
                self::EP,
                [],
                $this->headers
            );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertUnprocessable();
    }

    /**
     *
     */
    public function createUserAndGenerateAccessToken()
    {
        $user = User::factory()->create();

        $token = $user->createToken('API Token')->plainTextToken;

        $headers['Authorization'] = "Bearer $token";

        $this->headers = $this->headers + $headers;

        return $user;
    }
}
