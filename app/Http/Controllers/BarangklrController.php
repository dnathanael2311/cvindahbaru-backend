<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barangklr;
use App\Models\Barang;

class BarangklrController extends Controller
{
    public function list_barangklr()
    {
        $data = Barangklr::with('barang')->get();
        return response()->json($data);
    }

    public function getAllBarang()
    {
        $barang = Barang::all();
        return response()->json($barang);
    }

    public function create(Request $request)
    {
        $barang = Barang::find($request->id_brg);

        if (!$barang) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }

        if ($barang->stok < $request->qty_klr) {
                return response()->json(['message' => 'Stok tidak cukup'], 400);
        }

        $barangklr = Barangklr::create([
            'id_brg'   => $request->id_brg,
            'qty_klr'  => $request->qty_klr,
            'tgl_klr'  => $request->tgl_klr,
            'desk_klr' => $request->desk_klr
        ]);

        $barang->stok -= $request->qty_klr;
        $barang->save();

        return $barangklr
            ? response()->json(['message' => 'Barang keluar berhasil disimpan'])
            : response()->json(['message' => 'Gagal menyimpan barang keluar'], 500);
    }
}
