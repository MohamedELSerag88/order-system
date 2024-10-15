<?php

namespace App\Http\Controllers\V1\Order;

use App\Events\PaymentCancelled;
use App\Events\PaymentSucceeded;
use App\Http\Controllers\Controller;
use App\Http\Helpers\StripeHelper;
use App\Http\Repositories\OrderRepository;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    //
    public function __construct(
        protected OrderRepository $orderRepository
    ) {
    }
    public function paymentCompleted(Request $request){
        $id = $request->get('order_id');
        $order = $this->orderRepository->find($id);
        if(!$order){
            return $this->orderRepository->response->statusFail("Order not found!");
        }
        event(new PaymentSucceeded($order));
        return $this->orderRepository->response->statusOk("Order paid successfully!");
    }

    public function paymentWebhook(Request $request){

        try {
            $event = StripeHelper::webhook($request);
            if($event){
                return $this->orderRepository->response->statusOk("Order Updated successfully!");
            }
            else{
                return $this->orderRepository->response->statusFail("Order not found!");
            }
        } catch (\UnexpectedValueException $e) {
            return $this->orderRepository->response->statusFail("invalid payload");
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return $this->orderRepository->response->statusFail("invalid signature");
        }
    }
}
