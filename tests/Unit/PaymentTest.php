<?php



use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Repositories\OrderRepository;
use App\Http\Helpers\StripeHelper;

class PaymentTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    use RefreshDatabase;



    public function test_success_payment_order(): void
    {
        $this->addUser();
        $order = $this->addOrder();
        $response = $this->getJson('/api/v1/payment-completed?order_id='.$order->id);
        $response->assertStatus(200)
            ->assertJson([
                "response"=> [
                    "status"=> "OK",
                    "message"=> "Order paid successfully!"
                ]
            ]);
    }

    public function test_fail_payment_order(): void
    {
        $response = $this->getJson('/api/v1/payment-completed?order_id=5');
        $response->assertStatus(200)
            ->assertJson([
                "response"=> [
                    "status"=> "FAILED",
                    "message"=> "Order not found!"
                ]
            ]);
    }


    public function test_returns_fail_on_invalid_signature()
    {
        $this->mock(StripeHelper::class, function ($mock) {
            $mock->shouldReceive('webhook')
                ->andReturn(true);
        });
        $response = $this->postJson('/api/v1/webhook/stripe', []);

        $response->assertStatus(200)->assertJson([
            "response"=>[
                "status"=>"FAILED",
                "message"=>"invalid signature"
                ]
        ]);
    }






}
