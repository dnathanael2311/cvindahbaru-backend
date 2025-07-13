<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailRetur;

class DetailReturController extends Controller
{
    // Menampilkan semua detail retur beserta relasi ke retur dan barang
    public function list_detailretur(Request $request)
    {
        $data = DetailRetur::with(['retur', 'barang'])->get(); 
        return response()->json($data);
    }

    public function listByIdRetur(Request $request)
    {
        $request->validate([
            'id_rt' => 'required|exists:retur,id_rt'
        ]);

        $data = DetailRetur::with('barang')
            ->where('id_rt', $request->id_rt)
            ->get();

        return response()->json($data);
    }


    // Menambahkan detail retur baru
    public function create(Request $request)
    {
        $detailretur = DetailRetur::create([
            'id_rt'   => $request->id_rt,
            'id_brg'  => $request->id_brg,
            'qty_rt'  => $request->qty_rt,
            'alasan'  => $request->alasan
        ]);

        return $detailretur
            ? response()->json(['message' => 'Success!'])
            : response()->json(['message' => 'Failed!'], 500);
    }

    // Menghapus detail retur berdasarkan id_rt dan id_brg
    public function delete(Request $request)
    {
        $deleted = DetailRetur::where('id_rt', $request->id_rt)
            ->where('id_brg', $request->id_brg)
            ->delete();

        return $deleted
            ? response()->json(['message' => 'Success!'])
            : response()->json(['message' => 'Failed!'], 500);
    }

    // Mengupdate detail retur berdasarkan id_rt dan id_brg
    public function update(Request $request)
    {
        $detailretur = DetailRetur::where('id_rt', $request->id_rt)
            ->where('id_brg', $request->id_brg)
            ->first();

        if (!$detailretur) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $detailretur->update([
            'qty_rt' => $request->qty_rt,
            'alasan' => $request->alasan
        ]);

        return response()->json(['message' => 'Success!']);
    }
}
