<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\Shipping;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $customer = Auth::user()->customer;
        $orders = Order::where('customer_id', $customer->id)
            ->with(['orderItems.product', 'payment', 'shipping'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $customer = Auth::user()->customer;
        $order = Order::where('customer_id', $customer->id)
            ->with(['orderItems.product', 'payment', 'shipping'])
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    public function checkout()
    {
        $customer = Auth::user()->customer;
        $cart = Cart::where('customer_id', $customer->id)->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty!');
        }

        $cartItems = $cart->cartItems()->with('product')->get();
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->qty;
        });

        $addresses = $customer->addresses;

        return view('orders.checkout', compact('cartItems', 'subtotal', 'addresses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $customer = Auth::user()->customer;
        $cart = Cart::where('customer_id', $customer->id)->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty!');
        }

        DB::beginTransaction();

        try {
            $cartItems = $cart->cartItems()->with('product')->get();
            $totalAmount = $cartItems->sum(function ($item) {
                return $item->product->price * $item->qty;
            });

            $shippingFee = 5.00;

            $order = Order::create([
                'customer_id' => $customer->id,
                'order_code' => 'ORD-' . strtoupper(Str::random(10)),
                'status' => 'pending',
                'total_amount' => $totalAmount + $shippingFee,
                'shipping_fee' => $shippingFee,
                'payment_method' => $request->payment_method,
                'shipping_address' => $request->shipping_address,
            ]);

            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->qty,
                    'price' => $cartItem->product->price,
                ]);

                $inventory = Inventory::where('product_id', $cartItem->product_id)->first();
                if ($inventory) {
                    $inventory->decrement('qty_instock', $cartItem->qty);
                    $inventory->increment('ordered_qty', $cartItem->qty);
                }
            }

            Payment::create([
                'order_id' => $order->id,
                'method' => $request->payment_method,
                'amount' => $totalAmount + $shippingFee,
                'status' => 'pending',
            ]);

            Shipping::create([
                'order_id' => $order->id,
                'shipping_method' => 'Standard Shipping',
                'status' => 'pending',
            ]);

            $cart->cartItems()->delete();

            DB::commit();

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to place order. Please try again.');
        }
    }

    public function cancel($id)
    {
        $customer = Auth::user()->customer;
        $order = Order::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->findOrFail($id);

        DB::beginTransaction();

        try {
            foreach ($order->orderItems as $item) {
                $inventory = Inventory::where('product_id', $item->product_id)->first();
                if ($inventory) {
                    $inventory->increment('qty_instock', $item->quantity);
                    $inventory->decrement('ordered_qty', $item->quantity);
                }
            }

            $order->update(['status' => 'cancelled']);
            $order->payment->update(['status' => 'failed']);

            DB::commit();

            return redirect()->back()->with('success', 'Order cancelled successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to cancel order.');
        }
    }
}