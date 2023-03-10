<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (OrderStatus::getAllOrderStatusConstants() as $orderStatus) {
            OrderStatus::updateOrCreate([
                'code' => $orderStatus
            ], [
                'name' => ucfirst($orderStatus),
            ]);
        }
    }
}
