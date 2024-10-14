<?php

namespace App\Http\Controllers\V1\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderRequest;

class OrderController extends Controller
{
    //

    public function index(){

    }


    public function store(OrderRequest $request){
        $data = $request->validated();

    }

    public function update(){

    }
}
