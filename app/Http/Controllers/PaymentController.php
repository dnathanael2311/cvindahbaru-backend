<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    // Menampilkan seluruh data payment dengan relasi ke checkout
    public function list_payment(Request $request)
    {
        $data = Payment::with('checkout')->get();
        return response()->json($data);
    }

    // Menambahkan data payment baru
    public function create(Request $request)
    {
        $payment = Payment::create([
            'id_checkout'         => $request->id_checkout,
            'transaction_time'    => $request->transaction_time,
            'transaction_status'  => $request->transaction_status,
            'pdf_url'             => $request->pdf_url
        ]);

        return $payment ? response('Success!') : response('Failed!');
    }
    public function getSnapToken(Request $request)
{
    Config::$serverKey = config('services.midtrans.server_key');
    Config::$isProduction = false;
    Config::$isSanitized = true;
    Config::$is3ds = true;

    $params = [
        'transaction_details' => [
            'order_id' => 'ORDER-' . uniqid(),
            'gross_amount' => $request->total,
        ],
        'customer_details' => [
            'first_name' => $request->nama,
            'email' => $request->email,
            'phone' => $request->no_hp,
        ]
    ];

    $snapToken = Snap::getSnapToken($params);
    return response()->json(['token' => $snapToken]);
}
}
