<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Retur;

class ReturController extends Controller
{
    // ✅ Menampilkan semua data retur beserta relasi order
    public function list_retur()
    {
        $data = Retur::with('order.checkout.pelanggan')->get();
        return response()->json($data);
    }

    // ✅ Menampilkan retur yang diajukan oleh pelanggan tertentu
public function listByOrders(Request $request)
{
    $idOrders = $request->input('id_orders');

    // Ubah jadi array kalau cuma satu item
    if (!is_array($idOrders)) {
        $idOrders = [$idOrders];
    }

    $data = Retur::with('order')
        ->whereIn('id_order', $idOrders)
        ->get();

    return response()->json($data);
}

    // ✅ Tambah retur baru
    public function create(Request $request)
    {
        try {
            $request->validate([
                'id_order' => 'required|exists:order,id_order',
                'tgl_rt' => 'required|date',
                'st_retur' => 'required|string'
            ]);
            $existing = \App\Models\Retur::where('id_order', $request->id_order)->first();
            if ($existing) {
                return response()->json([
                    'message' => 'Retur untuk order ini sudah diajukan sebelumnya.'
                ], 409); // Conflict
            }
            $retur = Retur::create([
                'id_order' => $request->id_order,
                'tgl_rt' => $request->tgl_rt,
                'st_retur' => $request->st_retur
            ]);

            return response()->json($retur, 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ Show detail retur (beserta order + checkout)
public function show(Request $request)
    {
        $request->validate([
            'id_rt' => 'required|exists:retur,id_rt'
        ]);

        return Retur::with('order.checkout')->findOrFail($request->id_rt);
    }

    // ✅ Update status retur (misalnya dari admin)
    public function update(Request $request)
    {
        $request->validate([
            'id_rt' => 'required|exists:retur,id_rt',
            'st_retur' => 'required|string'
        ]);

        $retur = Retur::find($request->id_rt);
        $retur->st_retur = $request->st_retur;
        $retur->save();

        return response()->json(['message' => 'Update berhasil']);
    }

    // ✅ Hapus retur (opsional)
    public function delete(Request $request)
    {
        $request->validate([
            'id_rt' => 'required|exists:retur,id_rt'
        ]);

        try {
            // Hapus dulu detailretur yang terkait
            DetailRetur::where('id_rt', $request->id_rt)->delete();

            // Lalu hapus retur
            $deleted = Retur::destroy($request->id_rt);

            return $deleted
                ? response()->json(['message' => 'Data retur dan detailnya berhasil dihapus.'])
                : response()->json(['message' => 'Gagal menghapus data retur.'], 500);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
