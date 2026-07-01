<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $request->validate(['order_id' => 'required|string']);

        $payment = Payment::where('midtrans_order_id', $request->order_id)
            ->where('user_id', auth()->id())
            ->where('status', 'initiated')
            ->first();

        if (!$payment) {
            return response()->json(['ok' => false, 'message' => 'Payment not found'], 404);
        }

        $payment->update(['status' => 'pending']);
        $payment->invoice->update(['status' => 'pending_verification']);

        Log::info('[MidtransCallback] Payment updated to pending: ' . $request->order_id);

        return response()->json(['ok' => true]);
    }
}
