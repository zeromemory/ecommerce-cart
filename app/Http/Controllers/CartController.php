<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = auth()->user()
            ->cartItems()
            ->with('product')
            ->get();

        return Inertia::render('Cart/Index', [
            'cartItems' => $cartItems,
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if product has enough stock
        if ($product->stock_quantity < $request->quantity) {
            return back()->with('error', 'Not enough stock available');
        }

        // Check if item already exists in cart
        $cartItem = CartItem::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($product->stock_quantity < $newQuantity) {
                return back()->with('error', 'Not enough stock available');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return back()->with('success', 'Product added to cart');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Check if product has enough stock
        if ($cartItem->product->stock_quantity < $request->quantity) {
            return back()->with('error', 'Not enough stock available');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated');
    }

    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();

        return back()->with('success', 'Item removed from cart');
    }
}
