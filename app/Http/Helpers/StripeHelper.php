<?php

namespace App\Http\Helpers;
use App\Events\PaymentCancelled;
use App\Events\PaymentFailed;
use Illuminate\Support\Facades\URL;
use Stripe\StripeClient;
use Stripe\Charge;
use App\Events\PaymentSucceeded;
use App\Models\Order;
class StripeHelper
{
    private $stripe;
    public function __construct()
    {
        $this->stripe = new StripeClient(env('STRIPE_SK'));
    }

    public function checkout($order )
    {
        return $this->stripe->checkout->sessions->create([
            "mode" => "payment",
            "success_url" => route("payment.result", ['order_id' => $order->id]),
            "cancel_url" =>  URL::to("/"),
            "locale" => "auto",
            "line_items" => [
                [
                    "quantity" => $order->quantity,
                    "price_data" => [
                        "currency" => "egp",
                        "unit_amount" => $order->unit_price * 100,
                        "product_data" => [
                            "name" => $order->product_name,
                        ]
                    ]
                ]
            ],
            'metadata' => [
                'order_id' => $order->id
            ],
        ]);
    }

    public static function webhook($request){
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');
        $event = \Stripe\Webhook::constructEvent(
            $payload, $signature, $endpoint_secret
        );
        $order = Order::first($event->metadata->order_id);
        if($order){
            switch ($event->type) {
                case 'charge.updated':
                    if($event->paid)
                        event(new PaymentSucceeded($order));
                    else
                        event(new PaymentCancelled($order));
                    break;
                case 'charge.failed':
                    event(new PaymentCancelled($order));
                    break;
                case 'checkout.session.completed':
                case 'charge.succeeded' :
                    event(new PaymentSucceeded($order));
                    break;
            }
            return true;
        }

        else{
            return false;
        }
    }

}
