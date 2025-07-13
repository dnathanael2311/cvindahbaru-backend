<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailCheckout;

class DetailCheckoutController extends Controller
{
    // Menampilkan semua detail checkout beserta relasi ke checkout dan barang
    public function list_detailcheckout(Request $request)
    {
        $data = DetailCheckout::with(['checkout', 'barang'])->get();
        return response()->json($data);
    }

    // Menambahkan detail checkout baru
    public function create(Request $request)
    {
        $request->validate([
            'id_checkout'      => 'required|exists:checkout,id_checkout',
            'id_brg'           => 'required|exists:barang,id_brg',
            'dt_qty'           => 'required|integer|min:1',
            'dt_hargasatuan'   => 'required|numeric|min:0'
        ]);

        $detailcheckout = DetailCheckout::create([
            'id_checkout'      => $request->id_checkout,
            'id_brg'           => $request->id_brg,
            'dt_hargasatuan'   => $request->dt_hargasatuan,
            'dt_qty'           => $request->dt_qty
        ]);

        return $detailcheckout
            ? response()->json(['message' => 'Success!'])
            : response()->json(['message' => 'Failed!'], 500);
    }

    // Memperbarui detail checkout berdasarkan id_checkout dan id_brg
    public function update(Request $request)
    {
        $request->validate([
            'id_checkout'      => 'required|exists:detailcheckout,id_checkout',
            'id_brg'           => 'required|exists:barang,id_brg',
            'dt_qty'           => 'required|integer|min:1',
            'dt_hargasatuan'   => 'required|numeric|min:0'
        ]);

        $detailcheckout = DetailCheckout::where('id_checkout', $request->id_checkout)
            ->where('id_brg', $request->id_brg)
            ->first();

        if (!$detailcheckout) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Update manual karena tidak ada primary key
        DetailCheckout::where('id_checkout', $request->id_checkout)
            ->where('id_brg', $request->id_brg)
            ->update([
                'dt_qty'           => $request->dt_qty,
                'dt_hargasatuan'   => $request->dt_hargasatuan
            ]);

        return response()->json(['message' => 'Update berhasil']);
    }

    // Menghapus detail checkout berdasarkan id_checkout dan id_brg
    public function delete(Request $request)
    {
        $request->validate([
            'id_checkout' => 'required|exists:detailcheckout,id_checkout',
            'id_brg'      => 'required|exists:barang,id_brg'
        ]);

        $deleted = DetailCheckout::where('id_checkout', $request->id_checkout)
            ->where('id_brg', $request->id_brg)
            ->delete();

        return $deleted
            ? response()->json(['message' => 'Data berhasil dihapus'])
            : response()->json(['message' => 'Data tidak ditemukan atau gagal dihapus'], 500);
    }
}
