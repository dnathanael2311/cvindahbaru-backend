<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barangmsk;
use App\Models\Barang;

class BarangmskController extends Controller
{
    public function list_barangmsk()
    {
        $data = Barangmsk::with(['barang', 'expedisi'])->get();
        return response()->json($data);
    }

    public function getAllBarang()
    {
        $barang = Barang::with('kategori')->get();
        return response()->json($barang);
    }

    public function create(Request $request)
    {
        $barang = Barang::find($request->id_brg);

        if (!$barang) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }

        $barangmsk = Barangmsk::create([
            'id_brg'   => $request->id_brg,
            'id_exp'   => $request->id_exp,
            'qty_msk'  => $request->qty_msk,
            'tgl_msk'  => $request->tgl_msk,
            'desk_msk' => $request->desk_msk
        ]);

        // langsung tambah stok di DB
        $barang->increment('stok', $request->qty_msk);

        return $barangmsk
            ? response()->json(['message' => 'Barang masuk berhasil disimpan'])
            : response()->json(['message' => 'Gagal menyimpan barang masuk'], 500);
    }
}
