<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IsiKeranjang;
use App\Models\Keranjang;

class IsiKeranjangController extends Controller
{
    public function list_isikeranjang(Request $request)
    {
        $data = IsiKeranjang::with(['keranjang', 'barang'])->get();
        return response()->json($data);
    }

    public function create(Request $request)
    {
        $request->validate([
            'id_plg' => 'required|exists:pelanggan,id_plg',
            'id_brg' => 'required|exists:barang,id_brg',
            'qty'    => 'required|integer|min:1',
        ]);

        $idKeranjang = Keranjang::where('id_plg', $request->id_plg)->value('id_krg');

        if (!$idKeranjang) {
            return response()->json(['message' => 'Keranjang tidak ditemukan'], 404);
        }

        $item = IsiKeranjang::where('id_krg', $idKeranjang)
            ->where('id_brg', $request->id_brg)
            ->first();

        if ($item) {
            $item->krg_qty += $request->qty;
            $item->save();
            return response()->json(['message' => 'Jumlah barang diperbarui di keranjang.']);
        } else {
            IsiKeranjang::create([
                'id_krg'   => $idKeranjang,
                'id_brg'   => $request->id_brg,
                'krg_qty'  => $request->qty,
            ]);
            return response()->json(['message' => 'Barang ditambahkan ke keranjang.']);
        }
    }

public function getByKeranjang(Request $request)
{
    $id_krg = $request->id_krg;

    $items = IsiKeranjang::with('barang')
        ->where('id_krg', $id_krg)
        ->get();

    return response()->json($items);
}

    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_krg'   => 'required|integer|exists:keranjang,id_krg',
            'id_brg'   => 'required|integer|exists:barang,id_brg',
            'krg_qty'  => 'required|integer|min:1'
        ]);

        // Cari data berdasarkan kombinasi id_krg dan id_brg
        $isikeranjang = IsiKeranjang::where('id_krg', $request->id_krg)
            ->where('id_brg', $request->id_brg)
            ->first();

        if (!$isikeranjang) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Update secara manual karena tidak ada primary key
        $isikeranjang->krg_qty = $request->krg_qty;
        $isikeranjang->save(); // Ini aman walau tidak ada PK karena sudah pakai objek spesifik

        return response()->json(['message' => 'Update berhasil']);
    }


    public function delete(Request $request)
    {
        $deleted = IsiKeranjang::where('id_krg', $request->id_krg)
            ->where('id_brg', $request->id_brg)
            ->delete();

        return $deleted ? response('Success!') : response('Failed!');
    }

public function index(Request $request)
{
    $id_plg = $request->id_plg ?? null;
    $id_krg = $request->id_krg ?? null;

    if (!$id_krg && $id_plg) {
        $id_krg = Keranjang::where('id_plg', $id_plg)->value('id_krg');
    }

    if (!$id_krg) return response()->json([]);

    $items = IsiKeranjang::with('barang')
        ->where('id_krg', $id_krg)
        ->get()
        ->map(function ($item) {
            return [
                'id_brg' => $item->id_brg,
                'krg_qty' => $item->krg_qty,
                'barang' => [
                    'nama_brg' => $item->barang->nm_brg ?? 'Barang Tidak Diketahui',
                    'gambar'   => $item->barang->img ?? 'default.jpg',
                    'harga'    => $item->barang->harga_brg ?? 0,
                ]
            ];
        });

    return response()->json($items);
}


}
