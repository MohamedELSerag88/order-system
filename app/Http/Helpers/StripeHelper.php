<?php

namespace App\Http\Helpers;
use Illuminate\Support\Facades\URL;
use Stripe\StripeClient;
use Stripe\Charge;
class StripeHelper
{
    private $stripe;
    public function __construct()
    {
        $this->stripe = new StripeClient(env('STRIPE_SK'));
    }

    public function checkout($order , $paymentDetails)
    {
        $checkout_session = $this->stripe->checkout->sessions->create([
            "mode" => "payment",
            "success_url" => route("payment.result"),
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
            ]
        ]);
        return $checkout_session->url;
    }

    public function webhookPayment($request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = $this->stripe->webhookEndpoints->create($payload, $signature, $endpoint_secret);
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $order = \App\Models\Order::first();
                    event(new \App\Events\PaymentSucceeded($order));
                    break;
                case 'payment_intent.payment_failed':
                    break;
            }

            return response()->json(['status' => 'success']);
        } catch (\UnexpectedValueException $e) {
            return response()->json(['status' => 'invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['status' => 'invalid signature'], 400);
        }
    }

}
