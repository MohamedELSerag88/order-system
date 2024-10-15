<?php

namespace App\Http\Controllers\V1\Order;

use App\Http\Controllers\Controller;
use App\Http\Helpers\StripeHelper;
use App\Http\Repositories\OrderRepository;
use App\Http\Requests\Order\OrderRequest;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    //

    public function paymentWebhook(Request $request){
        $stripe = new StripeHelper();
        $payment_url = $stripe->webhookPayment($request);
    }
}
