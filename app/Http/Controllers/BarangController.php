<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function list_barang(Request $request)
    {
        $data = Barang::with('kategori')->get();
        return response()->json($data);
    }

    public function show(Request $request)
    {
        $barang = Barang::with('kategori')->where('id_brg', $request->id_brg)->first();

        if (!$barang) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }

        return response()->json($barang);
    }

    public function create(Request $request)
    {
        $imgPath = $request->hasFile('img')
            ? $request->file('img')->store('gambar', 'public')
            : '';

        $barang = Barang::create([
            'img'         => $imgPath,
            'nm_brg'      => $request->nm_brg,
            'id_ktg'      => $request->id_ktg,
            'merk'        => $request->merk,
            'stok'        => $request->stok,
            'satuan_brg'  => $request->satuan_brg,
            'berat'       => $request->berat,
            'diskon'      => $request->diskon ?? 0,
            'harga_brg'   => $request->harga_brg,
            'desk_brg'    => $request->desk_brg
        ]);

        return $barang->wasRecentlyCreated
            ? response()->json('Success!')
            : response()->json('Failed!');
    }

 public function update(Request $request)
{
    // Validasi awal: pastikan id_brg ada
    if (!$request->has('id_brg')) {
        return response()->json(['message' => 'ID Barang tidak dikirim!'], 400);
    }

    $barang = Barang::find($request->id_brg);

    if (!$barang) {
        return response()->json(['message' => 'Barang tidak ditemukan!'], 404);
    }

    // Jika user upload gambar baru, hapus gambar lama
    if ($request->hasFile('img')) {
        $file = $request->file('img');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('gambar', $filename, 'public');
        $barang->img = $path;
    }

    // Update data lainnya
    $barang->nm_brg      = $request->nm_brg;
    $barang->id_ktg      = $request->id_ktg;
    $barang->merk        = $request->merk;
    $barang->stok        = $request->stok;
    $barang->satuan_brg  = $request->satuan_brg;
    $barang->berat       = $request->berat;
    $barang->diskon      = $request->diskon ?? 0;
    $barang->harga_brg   = $request->harga_brg;
    $barang->desk_brg    = $request->desk_brg;

    if ($barang->save()) {
        return response()->json(['message' => 'Barang berhasil diperbarui']);
    } else {
        return response()->json(['message' => 'Gagal memperbarui barang'], 500);
    }
}

    public function delete(Request $request)
    {
        try {
            $barang = Barang::find($request->id_brg);

            if (!$barang) {
                return response()->json(['message' => 'Barang tidak ditemukan.'], 404);
            }

            // Hapus file gambar jika ada
            if ($barang->img && Storage::disk('public')->exists($barang->img)) {
                Storage::disk('public')->delete($barang->img);
            }

            $barang->delete();

            return response()->json(['message' => 'Barang berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus barang. Mungkin masih digunakan di tabel lain.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getAllBarang()
    {
        $barang = Barang::with('kategori')->get();
        return response()->json($barang);
    }

}