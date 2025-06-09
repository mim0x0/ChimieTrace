<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PaypalServerSdkLib\Models\Order;

class ApiController extends Controller
{
    public function handleWebhook(Request $request) {
        $event = $request->all();
        if ($event['event_type'] === 'PAYMENT.CAPTURE.COMPLETED') {
            // $orderId = $event['resource']['supplementary_data']['related_ids']['order_id'];
            // Mark your order as Paid in DB
        }
            // Log::info('PayPal Webhook:', $request->all());
            // return response()->json(['status' => 'OK']);
        // dd('testtt');

        // $data = $request->all();

        // dd($data);

        // // Validate webhook signature (optional but recommended)
        // \Log::info('PayPal Webhook Received', $data);

        // if ($data['event_type'] === 'PAYMENT.CAPTURE.COMPLETED') {
        //     $orderId = $data['resource']['id'];
        //     $amount = $data['resource']['amount']['value'];

        //     // Find your order, mark as paid
        //     $order = Order::where('paypal_order_id', $orderId)->first();

        //     if ($order) {
        //         $order->status = 'paid';
        //         $order->save();

        //         // Trigger payout to supplier
        //         $this->paySupplier($order);
        //     }
        // }
    }
}
