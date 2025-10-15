<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Payment;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function createIntent(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $intent = PaymentIntent::create([
            'amount' => $request->amount * 100, // Stripe works in cents
            'currency' => 'usd',
            'automatic_payment_methods' => ['enabled' => true],
            'metadata' => [
                'user_id' => $request->user()->id ?? null,
                'order_id' => $request->order_id ?? null,
            ],
        ]);

        // Save to DB
        Payment::create([
            'payment_intent_id' => $intent->id,
            'amount' => $request->amount,
            'currency' => 'usd',
            'status' => $intent->status,
            'metadata' => $intent->metadata,
        ]);

        return response()->json([
            'clientSecret' => $intent->client_secret,
        ]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
            'status' => 'required|string',
        ]);

        $payment = Payment::where('payment_intent_id', $request->payment_intent_id)->first();
        if ($payment) {
            $payment->update(['status' => $request->status]);
        }

        return response()->json(['success' => true]);
    }
}
