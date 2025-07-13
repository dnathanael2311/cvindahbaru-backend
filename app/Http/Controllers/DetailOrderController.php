<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailOrder;

class DetailOrderController extends Controller
{
    // Menampilkan semua detail order beserta relasi ke order dan barang
    public function list_detailorder(Request $request)
    {
        $data = DetailOrder::with(['order', 'barang'])->get();
        return response()->json($data);

        // Ambil qty dari data pembelian
        $detailOrder = DetailOrder::where('id_order', $request->id_order)
            ->where('id_brg', $request->id_brg)
            ->first();

        if (!$detailOrder) {
            return response()->json(['message' => 'Data order tidak ditemukan'], 404);
        }

        if ($request->qty_rt > $detailOrder->dor_qty) {
            return response()->json(['message' => 'Qty retur melebihi jumlah pembelian'], 422);
        }

    }

    // Menambahkan detail order baru
    public function create(Request $request)
    {
        $detailorder = DetailOrder::create([
            'id_order'          => $request->id_order,
            'id_brg'            => $request->id_brg,
            'dor_qty'           => $request->dor_qty,
            'dor_hargasatuan'   => $request->dor_hargasatuan
        ]);

        if ($detailorder) {
            return response()->json(['message' => 'Success!']);
        } else {
            return response()->json(['message' => 'Failed!'], 500);
        }
    }

    // Menghapus detail order berdasarkan id_order dan id_brg
    public function delete(Request $request)
    {
        $deleted = DetailOrder::where('id_order', $request->id_order)
            ->where('id_brg', $request->id_brg)
            ->delete();

        return $deleted
            ? response()->json(['message' => 'Success!'])
            : response()->json(['message' => 'Failed!'], 500);
    }

    public function getDetailOrder(Request $request)
{
    $request->validate([
        'id_order' => 'required|exists:order,id_order'
    ]);

    $detail = DetailOrder::with('barang') // pastikan relasi barang dimuat
        ->where('id_order', $request->id_order)
        ->get();

    return response()->json($detail);
}


}
