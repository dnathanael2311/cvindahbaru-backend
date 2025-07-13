<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RajaOngkirController extends Controller
{
    protected $key;
    protected $base;

    public function __construct()
    {
        $this->key = config('services.rajaongkir.key');
        $this->base = rtrim(config('services.rajaongkir.base_url'), '/');

        // Logging untuk debugging
        Log::info('RAJAONGKIR_KEY:', [$this->key]);
        Log::info('RAJAONGKIR_BASE_URL:', [$this->base]);
    }

    
public function provinces()
{
    try {
        $url = "{$this->base}/province";

        $response = Http::withHeaders([
            'key' => $this->key
        ])->get($url, ['search' => '']); // ← sesuaikan jika search wajib

        if ($response->failed()) {
            Log::error('Komerce error (provinces):', [$response->body()]);
            return response()->json(['message' => 'Gagal mengambil data provinsi dari Komerce.'], 500);
        }

        $data = $response->json();

        return response()->json([
            'data' => $data['data'] ?? [] // ← pastikan struktur ini
        ]);
    } catch (\Exception $e) {
        Log::error('Exception RajaOngkirController@provinces:', [$e->getMessage()]);
        return response()->json(['message' => 'Terjadi kesalahan saat mengambil data provinsi.'], 500);
    }
}



    public function cities($provinceId)
{
    try {
        $url = "{$this->base}/city/{$provinceId}";
        $response = Http::withHeaders([
            'key' => $this->key
        ])->get($url, ['search' => '']);

        if ($response->failed()) {
            Log::error('Komerce error (cities):', [$response->body()]);
            return response()->json(['message' => 'Gagal mengambil data kota dari Komerce.'], 500);
        }

        return response()->json($response->json());
    } catch (\Exception $e) {
        Log::error('Exception RajaOngkirController@cities:', [$e->getMessage()]);
        return response()->json(['message' => 'Terjadi kesalahan saat mengambil data kota.'], 500);
    }
}




    public function cost(Request $request)
{
    try {
        $barang = \App\Models\Barang::find($request->id_brg);

        if (!$barang) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }
        $response = Http::withHeaders([
            'x-api-key' => config('services.shipping.key')
        ])->get(config('services.shipping.base_url') . '/calculate', [
            'origin'      => $request->origin,
            'destination' => $request->destination,
            'weight'      => $barang->berat,
            'courier'     => $request->courier,
            'item_value'  => intval($barang->harga_brg),

            
        ]);

        if ($response->failed()) {
            \Log::error('❌ Komerce error:', [$response->body()]);
            return response()->json(['message' => 'Gagal mengambil data ongkir dari Komerce.'], 500);
        }

        return response()->json($response->json());
    } catch (\Exception $e) {
        \Log::error('❌ Exception:', [$e->getMessage()]);
        return response()->json(['message' => 'Terjadi kesalahan saat menghitung ongkir.'], 500);
    }
}





}
