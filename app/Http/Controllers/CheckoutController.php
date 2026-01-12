<?php

namespace App\Http\Controllers;

use App\Jobs\CheckLowStockJob;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        $user = auth()->user();
        $cartItems = $user->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty');
        }

        try {
            DB::beginTransaction();

            // Calculate total
            $total = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $total,
                'status' => 'completed',
            ]);

            // Create order items and update stock
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;

                // Check stock availability
                if ($product->stock_quantity < $cartItem->quantity) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $cartItem->quantity,
                    'price' => $product->price,
                ]);

                // Update stock
                $product->decrement('stock_quantity', $cartItem->quantity);

                // Dispatch low stock notification job if stock is low (threshold: 5)
                if ($product->stock_quantity <= 5) {
                    CheckLowStockJob::dispatch($product);
                }
            }

            // Clear cart
            $user->cartItems()->delete();

            DB::commit();

            return redirect()->route('products.index')
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
