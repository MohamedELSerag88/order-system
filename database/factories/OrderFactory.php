<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Order::class;
    public function definition(): array
    {
        $quantity = fake()->randomDigitNotZero();
        $unitPrice = fake()->randomFloat(2, 1, 100);
        $status = ["Pending",  "Paid", "Canceled"];
        return [

            'product_name' => fake()->word(),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total' => $quantity * $unitPrice,
            'status' => $status[array_rand($status,1)],
        ];
    }


}
