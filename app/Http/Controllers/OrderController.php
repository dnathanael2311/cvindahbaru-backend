<?php

namespace App\Http\Controllers;
use App\Models\DetailOrder;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // Menampilkan semua order beserta relasi pelanggan dan checkout (yang memuat ekspedisi)
    public function list_order()
{
    $orders = Order::with(['checkout', 'pelanggan'])->get();
    return response()->json($orders);
}


    // Menambahkan order baru
    public function create(Request $request)
    {
        $order = Order::create([
            'id_plg' => $request->id_plg,
            'id_checkout' => $request->id_checkout
        ]);

        return $order ? response('Success!') : response('Failed!');
    }

    // Menghapus order berdasarkan id_order
    public function delete(Request $request)
    {
        $deleted = Order::where('id_order', $request->id_order)->delete();

        return $deleted ? response('Success!') : response('Failed!');
    }

    public function show($id)
{
    $order = Order::with(['pelanggan', 'checkout', 'detailorder.barang'])->find($id);

    if (!$order) {
        return response()->json(['message' => 'Order tidak ditemukan'], 404);
    }

    return response()->json($order);
}
public function getDetailOrder(Request $request)
{
    $request->validate([
        'id_order' => 'required|exists:order,id_order'
    ]);

    $detail = \App\Models\DetailOrder::with('barang')
                ->where('id_order', $request->id_order)
                ->get();

    return response()->json($detail);
}


}
