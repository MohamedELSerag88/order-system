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
        $this->AddOrder();
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
            "quantity"=>4,
            "payment_details"=>[
                "card_number"=>"1234567891011123",
                "pin"=>1234,
                "cvv"=>"123"
            ]
        ]);
        $order =  Order::first();
        $response->assertStatus(200)
            ->assertJson([
                "response" => [
                    "status" =>"OK",
                   "data"=> [
                        "id"=> $order->id,
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

    public function AddOrder(): void{
        Order::create([
            "product_name"=>"test",
            "quantity"=>1,
            "unit_price"=>1,
            "total"=>1,
            "status"=>"Pending",
            "user_id"=>User::first()->id,
        ]);
    }

}
