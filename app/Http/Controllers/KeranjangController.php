<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keranjang;
use App\Models\IsiKeranjang;

class KeranjangController extends Controller
{
    // List isi keranjang berdasarkan id_krg
    public function list_isikeranjang(Request $request)
    {
        $data = IsiKeranjang::with(['barang'])
            ->where('id_krg', $request->id_krg)
            ->get();

        return response()->json($data);
    }

    // Dapatkan keranjang berdasarkan id_plg
    public function getByPelanggan(Request $request)
{
    $id_plg = $request->id_plg;

    $id_keranjang = \App\Models\Keranjang::where('id_plg', $id_plg)->first();

    if (!$id_keranjang) {
        return response()->json(['message' => 'Keranjang tidak ditemukan'], 404);
    }

    return response()->json($id_keranjang);
}

}
