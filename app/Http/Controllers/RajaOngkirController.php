<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Pelanggan;
use App\Models\Keranjang;
use App\Models\IsiKeranjang;

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
        $url = "{$this->base}/destination/province";

        $response = Http::withHeaders([
            'key' => $this->key
        ])->get($url); // ← sesuaikan jika search wajib

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
        $url = "{$this->base}/destination/city/{$provinceId}";
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
            $request->validate([
    'item_value' => 'required|numeric|min:1',
    'weight' => 'required|numeric|min:1',
    'id_brg' => 'required|exists:barang,id_brg',
    'id_plg' => 'required|exists:pelanggan,id_plg',
]);
Log::info('Params cost() called with:', $request->all());

            $barang = \App\Models\Barang::find($request->id_brg);
            $pelanggan = \App\Models\Pelanggan::find($request->id_plg);

            if (!$barang || !$pelanggan) {
                return response()->json(['message' => 'Barang atau pelanggan tidak ditemukan'], 404);
            }

            $destination = $pelanggan->kota; // pastikan kamu punya ini di DB
            $beratTotal = $request->weight;
            $subtotal = $request->item_value;

            $response = Http::withHeaders([
                'x-api-key' => config('services.shipping.key')
            ])->get(config('services.shipping.base_url') . '/calculate', [
                'shipper_destination_id'      => $request->origin ?? '512', // fallback default kota asal
                'receiver_destination_id' => $destination,
                'weight'      => $beratTotal,
                // 'courier'     => $request->courier ?? 'jne',
                'item_value'  => intval($subtotal),
            ]);

            if ($response->failed()) {
                \Log::error('❌ Komerce error:', [$response->body()]);
                return response()->json(['message' => 'Gagal mengambil data ongkir dari Komerce.'], 500);
            }
            Log::info($response);

            return response()->json($response->json());
        } catch (\Exception $e) {
            \Log::error('❌ Exception:', [$e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan saat menghitung ongkir.'], 500);
        }
    }


    public function getOngkirByPelanggan(Request $request)
    {
        $pelanggan = Pelanggan::find($request->id_plg);

        if (!$pelanggan || !$pelanggan->id_kota) {
            return response()->json(['message' => 'Data pelanggan tidak lengkap'], 400);
        }

        $keranjang = Keranjang::where('id_plg', $pelanggan->id_plg)->first();
        Log::info('isi keranjang', [$keranjang]);

        if (!$keranjang) {
            return response()->json(['message' => 'Keranjang tidak ditemukan'], 404);
        }
        
        $items = IsiKeranjang::where('id_krg', $keranjang->id_krg)->get();
        $beratTotal = 0;

        foreach ($items as $item) {
            $barang = Barang::find($item->id_brg);
            if ($barang) {
                $beratTotal += $barang->berat * $item->krg_qty;
            }
        }
        
        // Kirim request ke RajaOngkir
        $response = Http::withHeaders([
            'key' => config('services.rajaongkir.key')
        ])->post('https://api.rajaongkir.com/starter/cost', [
            'origin'        => 501, // ID kota asal, ganti sesuai data toko kamu
            'destination'   => $pelanggan->id_kota, // dari database pelanggan
            'weight'        => $beratTotal ?: 1000, // default 1 kg jika kosong
            'courier'       => 'jne'
        ]);

        if ($response->failed()) {
            return response()->json(['message' => 'Gagal mengambil ongkir dari RajaOngkir'], 500);
        }

        $data = $response->json();
        Log::info($data);
        // Ambil hanya ongkir pertama
        $ongkir = $data['rajaongkir']['results'][0]['costs'][0]['cost'][0]['value'] ?? 0;

        return response()->json(['ongkir' => $ongkir]);
    }

}
