<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;

class MidtransController extends Controller
{
    public function getSnapToken(Request $request)
    {
        // Setup Midtrans config
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = false; // true jika sudah live
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Data transaksi
        $params = [
            'transaction_details' => [
                'order_id' => uniqid('ORDER-'),
                'gross_amount' => $request->total, // Total transaksi
            ],
            'customer_details' => [
                'first_name' => $request->nama,
                'email' => $request->email,
                'phone' => $request->no_hp,
            ],
        ];

        // Dapatkan Snap token
        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Gagal mendapatkan Snap token',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}

