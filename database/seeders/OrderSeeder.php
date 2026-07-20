<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Safety Guard for Production
        if (app()->environment('production')) {
            abort(403, 'Cannot run seeders in production environment.');
        }

        // Truncate existing orders
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Order::truncate();
        OrderItem::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $products = Product::all();
        if ($products->isEmpty()) {
            return;
        }

        $names = ['John Doe', 'Jane Smith', 'Brad Wilson', 'Alice Johnson', 'Michael Brown', 'Emily Davis', 'David Miller', 'Sarah Wilson', 'James Taylor', 'Mary Thomas'];
        $addresses = ['123 Main St, Mumbai', '456 Park Rd, Delhi', '789 Link Rd, Bangalore', '101 Outer Ring Rd, Hyderabad', '202 MG Road, Pune'];
        
        // Let's seed 150 orders spread over the last 18 months
        for ($i = 0; $i < 150; $i++) {
            // Generate random date between 18 months ago and now
            $date = Carbon::now()->subSeconds(rand(0, 18 * 30 * 24 * 60 * 60));
            
            // Random customer details
            $name = $names[array_rand($names)];
            $phone = '9198765' . rand(10000, 99999);
            $address = $addresses[array_rand($addresses)];
            
            // Order status probability: 70% delivered, 15% pending, 15% canceled
            $randStatus = rand(1, 100);
            if ($randStatus <= 70) {
                $status = 'delivered';
            } elseif ($randStatus <= 85) {
                $status = 'pending';
            } else {
                $status = 'canceled';
            }
            
            // Calculate items
            $numItems = rand(1, 3);
            $subtotal = 0;
            $itemsData = [];
            
            for ($j = 0; $j < $numItems; $j++) {
                $product = $products->random();
                $qty = rand(1, 2);
                $price = $product->price;
                $subtotal += $price * $qty;
                
                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $price,
                    'quantity' => $qty,
                ];
            }
            
            // Shipping fee (e.g. 50 if subtotal < 500, else 0)
            $shippingFee = ($subtotal < 500) ? 50 : 0;
            $total = $subtotal + $shippingFee;
            
            // Create Order
            $order = Order::create([
                'customer_name' => $name,
                'customer_phone' => $phone,
                'customer_address' => $address,
                'notes' => rand(0, 1) ? 'Please deliver in evening.' : null,
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total' => $total,
                'status' => $status,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
            
            // Create Order Items
            foreach ($itemsData as $item) {
                OrderItem::create(array_merge($item, [
                    'order_id' => $order->id,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]));
            }
        }
    }
}
