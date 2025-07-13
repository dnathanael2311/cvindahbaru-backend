<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pelanggan;
use App\Models\Keranjang;

class PelangganController extends Controller
{
    // ✅ Ambil semua pelanggan
    public function list_pelanggan()
    {
        return response()->json(Pelanggan::all());
    }

    // ✅ Ambil 1 pelanggan berdasarkan ID
    public function show($id)
    {
        $pelanggan = Pelanggan::find($id);

        if (!$pelanggan) {
            return response()->json(['message' => 'Pelanggan tidak ditemukan'], 404);
        }

        return response()->json($pelanggan);
    }

    // ✅ Tambah pelanggan baru + keranjang
    public function create(Request $request)
    {
        if (strlen($request->pass_plg) !== 8) {
            return response()->json('Password harus tepat 8 karakter!', 400);
        }

        $exists = Pelanggan::where('user_plg', $request->user_plg)
            ->orWhere('no_plg', $request->no_plg)
            ->orWhere('email_plg', $request->email_plg)
            ->exists();

        if ($exists) {
            return response()->json('Akun ini sudah terdaftar', 409);
        }

        $pelanggan = Pelanggan::create([
            'user_plg'   => $request->user_plg,
            'nm_plg'     => $request->nm_plg,
            'alamat'     => $request->alamat,
            'provinsi'   => $request->provinsi, // nama provinsi
            'kota'       => $request->kota,     // nama kota
            'no_plg'     => $request->no_plg,
            'email_plg'  => $request->email_plg,
            'tgl_lahir'  => $request->tgl_lahir,
            'pass_plg'   => Hash::make($request->pass_plg)
        ]);

        Keranjang::create([
            'id_plg' => $pelanggan->id_plg
        ]);

        return response('Success!');
    }

    // ✅ Update data pelanggan
public function update(Request $request)
{
    try {
        $pelanggan = Pelanggan::where('id_plg', $request->id_plg)->first();

        if (!$pelanggan) {
            return response()->json(['message' => 'Pelanggan tidak ditemukan.'], 404);
        }

        $pelanggan->nm_plg = $request->nm_plg;
        $pelanggan->email_plg = $request->email_plg;
        $pelanggan->no_plg = $request->no_plg;
        $pelanggan->tgl_lahir = $request->tgl_lahir;
        $pelanggan->alamat = $request->alamat;
        $pelanggan->provinsi = $request->provinsi;
        $pelanggan->kota = $request->kota;
        $pelanggan->save();

        return response()->json(['message' => 'Berhasil diperbarui.']);
    } catch (\Exception $e) {
        \Log::error('Gagal update pelanggan: ' . $e->getMessage());
        return response()->json(['message' => 'Gagal menyimpan data.'], 500);
    }
}

    // ✅ Hapus data pelanggan dan keranjang
    public function delete(Request $request)
    {
        Keranjang::where('id_plg', $request->id_plg)->delete();
        Pelanggan::where('id_plg', $request->id_plg)->delete();
        return response('Success!');
    }

    // ✅ Login pelanggan
    public function login(Request $request)
    {
        $user = Pelanggan::where('user_plg', $request->user_plg)->first();

        if (!$user || !Hash::check($request->pass_plg, $user->pass_plg)) {
            return response()->json(['message' => 'Login gagal'], 401);
        }

        return response()->json([
            'id_plg' => $user->id_plg,
            'nama'   => $user->nm_plg,
            'token'  => 'dummy-token' // Ganti dengan token asli jika pakai sanctum/jwt
        ]);
    }
}
