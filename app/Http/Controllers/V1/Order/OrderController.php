<?php

namespace App\Http\Controllers\V1\Order;

use App\Http\Controllers\Controller;
use App\Http\Repositories\OrderRepository;
use App\Http\Requests\Order\OrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Resources\Order\OrderResource;

class OrderController extends Controller
{
    //
    public function __construct(
        protected OrderRepository $orderRepository
    ) {
    }

    public function index(){
        return $this->orderRepository->all();
    }


    public function store(OrderRequest $request){
        return $this->orderRepository->create($request->validated());

    }

    public function update($id, UpdateOrderRequest $request){
        return $this->orderRepository->update($request->validated(),$id);
    }
}
