<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\BakongTransaction;

class BakongController extends Controller
{
    public function generateQr(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'currency' => 'required|string',
            'city' => 'nullable|string'
        ]);

        $hash = Str::uuid()->toString();

        $qrPayload = "000201010212"
            . "5204KHQR"
            . "5303" . $request->currency
            . "540" . strlen($request->amount) . $request->amount
            . "5802KH"
            . "5910MyShopName"
            . "6013" . ($request->city ?? 'Phnom Penh')
            . "6304ABCD";

        $transaction = BakongTransaction::create([
            'hash' => $hash,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'city' => $request->city ?? 'Phnom Penh',
            'qr_payload' => $qrPayload,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        return response()->json([
            'qr_payload' => $transaction->qr_payload,
            'hash' => $transaction->hash,
            'expires_at' => $transaction->expires_at->timestamp,
        ]);
    }

    public function checkStatus($hash)
    {
        $transaction = BakongTransaction::where('hash', $hash)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // simulate checking payment
        if ($transaction->expires_at->isPast()) {
            $transaction->update(['status' => 'expired']);
        }

        return response()->json([
            'hash' => $transaction->hash,
            'paid' => $transaction->status === 'paid',
            'status' => $transaction->status,
        ]);
    }

    // Optional: simulate payment callback
    public function markAsPaid($hash)
    {
        $transaction = BakongTransaction::where('hash', $hash)->firstOrFail();
        $transaction->update(['status' => 'paid']);

        return response()->json(['message' => 'Trans]()
action marked as paid']);
    }
}