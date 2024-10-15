<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id ,
            "product_name" => $this->product_name ,
            "unit_price" => $this->unit_price ,
            "quantity" => $this->quantity,
            "total" => $this->total,
            "status" => $this->status,
            "date" => $this->created_at->format('d M,Y H:i'),
        ];
    }
}
