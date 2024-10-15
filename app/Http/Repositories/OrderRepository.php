<?php

namespace App\Http\Repositories;

use App\Http\Filters\DatePipeline;
use App\Http\Filters\KeySearchPipeline;
use App\Http\Filters\PaginationPipeline;
use App\Http\Filters\SortPipeline;
use App\Http\Filters\StatusPipeline;
use App\Http\Helpers\StripeHelper;
use App\Http\Helpers\Traits\ApiPaginator;
use App\Http\Resources\Order\OrderResource;
use App\Http\Response\Response;
use App\Models\Order;
use Illuminate\Pipeline\Pipeline;

class OrderRepository implements BaseRepositoryInterface
{
    use ApiPaginator;
    public $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function all()
    {
        $orders = app(Pipeline::class)
            ->send(Order::query()->where("user_id",auth()->id()))
            ->through([
                PaginationPipeline::class,
                SortPipeline::class,
                DatePipeline::class,
                StatusPipeline::class,
                KeySearchPipeline::class,
            ])
            ->thenReturn();
        $collection = OrderResource::collection($orders);
        $data = $this->getPaginatedResponse($orders, $collection);
        return $this->response->statusOk($data);
    }

    public function create(array $data)
    {
        try {
            \DB::beginTransaction();
            $data["total"] = $data['unit_price'] * $data['quantity'];
            $data["status"] = "Pending";
            $data["user_id"] = auth()->id();
            $paymentDetails = $data["payment_details"];
            unset($data["payment_details"]);
            $order = Order::create($data);
            $stripe = new StripeHelper();
            $payment_url = $stripe->checkout($order, $paymentDetails);
            $order->payment_url = $payment_url;
            \DB::commit();
            return $this->response->statusOk(["data" =>new OrderResource($order)]);
        }
        catch (\Exception $exception){
            \DB::rollback();
            return $this->response->statusFail( $exception->getMessage());
        }

    }

    public function update(array $data, $id)
    {
    }


}
