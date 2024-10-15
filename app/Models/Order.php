<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'unit_price',
        'quantity',
        'total',
        'status',
        'user_id',
        'payment_url'
    ];

    public $search = ["product_name"];

    protected static function newFactory()
    {
        return OrderFactory::new();
    }
}
