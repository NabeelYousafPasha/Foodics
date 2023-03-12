<?php

namespace Tests\Feature\Order;

use App\Models\{Ingredient, IngredientNotification, Product, User};
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
     * product in payload must be unique
     *
     * @return void
     */
    public function test_order_must_contain_unique_products()
    {
        $user = $this->createUserAndGenerateAccessToken();

        $payload = $this->payload;
        $payload['products'][1] = $payload['products'][0];

        $response = $this->actingAs($user)
            ->json('POST',
                self::EP,
                $payload,
                $this->headers
            );

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('products.0.product_id');
    }

    /**
     * test to check if it notifies ingredient threshold in ingredient_notifications
     *
     * @return void
     */
    public function test_order_product_ingredient_notifications_are_empty_by_default()
    {
        $user = $this->createUserAndGenerateAccessToken();

        $response = $this->actingAs($user)
            ->json('POST',
                self::EP,
                $this->payload,
                $this->headers
            );

        $response->assertJson([
            'status' => 'Success',
        ]);
        $this->assertDatabaseMissing(IngredientNotification::class, [
            'id' => 1,
        ]);
    }

    /**
     * test to check if it notifies ingredient threshold in ingredient_notifications
     *
     * @return void
     */
    public function test_order_product_ingredients_threshold_are_notified_in_ingredient_notifications()
    {
        $user = $this->createUserAndGenerateAccessToken();

        $payload = $this->payload;
        $payload['products'][0]['quantity'] = 25;

        $response = $this->actingAs($user)
            ->json('POST',
                self::EP,
                $payload,
                $this->headers
            );

        $response->assertJson([
            'status' => 'Success',
        ]);
        $this->assertDatabaseCount(IngredientNotification::class, 1);
    }

    /**
     * test to check if it notifies ingredient threshold and not null last_threshold_notified_at
     *
     * @return void
     */
    public function test_order_product_ingredients_has_been_notified_and_column_last_threshold_notified_at_is_filled()
    {
        $user = $this->createUserAndGenerateAccessToken();

        $payload = $this->payload;
        $payload['products'][0]['quantity'] = 25;

        $response = $this->actingAs($user)
            ->json('POST',
                self::EP,
                $payload,
                $this->headers
            );

        $ingredientOnion = Ingredient::ofCode('onion')->first();

        $this->assertNotNull($ingredientOnion->last_threshold_notified_at);
    }

    /**
     * test to check if it throws exception
     *
     * @return void
     */
    public function test_order_throws_product_quantity_exception()
    {
        $user = $this->createUserAndGenerateAccessToken();

        $payload = $this->payload;
        $payload['products'][0]['quantity'] = 5000;

        $response = $this->actingAs($user)
            ->json('POST',
                self::EP,
                $payload,
                $this->headers
            );

        $response->assertUnprocessable();
        $response->assertJson([
            'status' => 'Error',
        ]);
        $response->assertSee([
            'status' => 'Error',
            'message' => 'Product Quantity Exceeded Stock: ',
        ]);
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
