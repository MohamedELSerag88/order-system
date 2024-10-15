<?php


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\Order;

class OrdersTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    use RefreshDatabase;

    public function test_list_orders_without_login(): void
    {
        $response = $this->getJson('/api/v1/orders');
        $response->assertStatus(401)
            ->assertJson([
                "message" => "Unauthenticated."
            ]);
    }

    public function test_list_orders_with_login(): void
    {
        $this->addUser();
        $this->actingAs(User::first());
        $response = $this->getJson('/api/v1/orders');
        $response->assertStatus(200)
            ->assertJsonStructure([
                "response" => [
                    "status",
                    "data",
                    "links"=> [
                        "first_page_url",
                        "last_page_url",
                        "next_page_url",
                        "prev_page_url"
                    ],
                    "meta"=> [
                        "path",
                        "current_page",
                        "from",
                        "per_page",
                        "to",
                        "total",
                        "pages"
                    ]
                ]
            ]);
    }
    public function test_list_orders_with_data(): void
    {
        $this->addUser();
        $this->actingAs(User::first());
        $order = $this->AddOrder();
        $response = $this->getJson('/api/v1/orders');
        $response->assertStatus(200)
            ->assertJsonStructure([
                "response" => [
                    "status",
                    "data",
                    "links"=> [
                        "first_page_url",
                        "last_page_url",
                        "next_page_url",
                        "prev_page_url"
                    ],
                    "meta"=> [
                        "path",
                        "current_page",
                        "from",
                        "per_page",
                        "to",
                        "total",
                        "pages"
                    ]
                ]
            ]);
    }

    public function test_list_orders_with_filters(): void
    {
        $this->addUser();
        $this->actingAs(User::first());
        $order = $this->AddOrder();
        $response = $this->getJson('/api/v1/orders');
        $response->assertStatus(200)
            ->assertJsonStructure([
                "response" => [
                    "status",
                    "data",
                    "links"=> [
                        "first_page_url",
                        "last_page_url",
                        "next_page_url",
                        "prev_page_url"
                    ],
                    "meta"=> [
                        "path",
                        "current_page",
                        "from",
                        "per_page",
                        "to",
                        "total",
                        "pages"
                    ]
                ]
            ]);
    }

    public function test_add_valid_order(): void
    {
        $this->addUser();
        $this->actingAs(User::first());
        $response = $this->postJson('/api/v1/orders',[
            "product_name"=>"Item name",
            "unit_price"=> 40.5,
            "quantity"=>4
        ]);
        $order = Order::first();
        $response->assertStatus(200)
            ->assertJson([
                "response" => [
                    "status" =>"OK",
                    "data"=> [
                        "id"=> $order->id,
                        "payment_url"=> $order->payment_url,
                        "product_name"=>"Item name",
                        "unit_price"=> 40.5,
                        "quantity"=>4,
                        "total"=>162,
                        "status"=> "Pending",
                        "date"=> \Carbon\Carbon::now()->format("d M,Y H:i")
                       ]
                ]
            ]);
    }
    public function test_add_order_with_missing_data(): void
    {
        $this->addUser();
        $this->actingAs(User::first());
        $response = $this->postJson('/api/v1/orders',[
            "quantity"=>4
        ]);
        $order = Order::first();
        $response->assertStatus(422)
            ->assertJson([
                'response' => [
                    "status" => "FAILED",
                    "message"=>"The product name field is required."
                ],
            ]);
    }

    public function test_update_order_with_missing_data(): void
    {
        $this->addUser();
        $this->actingAs(User::first());
        $response = $this->postJson('/api/v1/orders',[
            "quantity"=>4
        ]);
        $order = Order::first();
        $response->assertStatus(422)
            ->assertJson([
                'response' => [
                    "status" => "FAILED",
                    "message"=>"The product name field is required."
                ],
            ]);
    }

    public function test_user_without_permission_cannot_update_order()
    {
        $this->addUser();
        $this->actingAs(User::first());
        $order = $this->AddOrder();
        $response = $this->put('/api/v1/orders/'.$order->id, [
            "product_name"=>"Item name",
            "unit_price"=> 40.5,
            "quantity"=>4,
            "status"=> "Paid",
        ]);
        $response->assertStatus(403);
    }

    public function test_user_with_permission_can_update_order()
    {
        $user = $this->addPermission();
        $this->actingAs($user);
        $order = $this->AddOrder();

        $response = $this->put("/api/v1/orders/{$order->id}", [
            "product_name"=>"Item name",
            "unit_price"=> 40.5,
            "quantity"=>4,
            "status"=> "Paid",]);
        $response->assertStatus(200)->assertJson([
            "response" => [
                "status" =>"OK",
                "message" =>"Order updated successfully",
            ]
        ]);
    }

}
