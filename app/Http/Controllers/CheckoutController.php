<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\Checkout;
    use App\Models\Reward;
    use App\Models\Keranjang;
    use App\Models\IsiKeranjang;


    class CheckoutController extends Controller
    {
        // Menampilkan semua checkout beserta relasi pelanggan, keranjang, dan reward
        public function list_checkout(Request $request)
        {
            $data = Checkout::with(['pelanggan', 'keranjang', 'reward'])->get();
            return response()->json($data);
        }

        // Menambahkan checkout baru, memperhitungkan reward aktif
        public function create(Request $request)
        {
            $ongkir = $request->ongkir;
            $ttl_harga = $request->ttl_harga;

            // Cek apakah pelanggan memiliki reward aktif
            $reward = Reward::where('id_plg', $request->id_plg)
                            ->where('expired_rwd', '>=', now())
                            ->first();

            if ($reward) {
                if ($reward->jenis_rwd === 'diskon_total') {
                    $ttl_harga -= $reward->value_rwd;
                    $ttl_harga = max(0, $ttl_harga);
                } elseif ($reward->jenis_rwd === 'diskon_ongkir') {
                    $ongkir -= $reward->value_rwd;
                    $ongkir = max(0, $ongkir);
                }

                // Reward dihapus setelah digunakan
                $reward->delete();
            }

            $checkout = Checkout::create([
                'id_krg'      => $request->id_krg,
                'id_plg'      => $request->id_plg,
                'id_rwd'      => null, // reward sudah terpakai
                'ongkir'      => $ongkir,
                'ttl_harga'   => $ttl_harga,
                'tgl_checkout'=> $request->tgl_checkout,
                'kurir'       => $request->kurir
            ]);

            return $checkout
                ? response('Success!')
                : response('Failed!');
        }

            public function update(Request $request)
        {
            $request->validate([
                'id_checkout'  => 'required|exists:checkout,id_checkout',
                'ongkir'       => 'required|numeric|min:0',
                'ttl_harga'    => 'required|numeric|min:0',
                'tgl_checkout' => 'required|date',
                'kurir'        => 'required|string|max:50'
            ]);

            $checkout = Checkout::find($request->id_checkout);

            if (!$checkout) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }

            $checkout->update([
                'ongkir'       => $request->ongkir,
                'ttl_harga'    => $request->ttl_harga,
                'tgl_checkout' => $request->tgl_checkout,
                'kurir'        => $request->kurir
            ]);

            return response()->json(['message' => 'Update berhasil']);
        }


        // Menghapus data checkout
        public function delete(Request $request)
        {
            $deleted = Checkout::where('id_checkout', $request->id_checkout)->delete();
            return $deleted ? response('Success!') : response('Failed!');
        }

        public function index(Request $request)
        {
            $id_plg = $request->id_plg;

            $id_keranjang = Keranjang::where('id_plg', $id_plg)->value('id_krg');

            if (!$id_keranjang) {
                return response()->json([]);
            }

            $items = IsiKeranjang::with('barang')
                ->where('id_krg', $id_keranjang)
                ->get()
                ->map(function ($item) {
                    return [
                        'id_brg'   => $item->id_brg,
                        'krg_qty' => $item->krg_qty,
                        'barang'  => [
                            'nama_brg' => $item->barang->nama_brg ?? 'Barang Tidak Diketahui',
                            'harga'    => $item->barang->harga ?? 0,
                            'gambar'   => $item->barang->gambar ?? 'default.jpg',
                        ]
                    ];
                });

            return response()->json($items);
        }

        public function invoice(Request $request)
        {
            $id = $request->id_checkout;

            $checkout = Checkout::with(['pelanggan', 'detail.barang'])
                ->where('id_checkout', $id)
                ->first();

            if (!$checkout) {
                return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
            }

            $items = $checkout->detail->map(function ($d) {
                return [
                    'nama_brg' => $d->barang->nm_brg,
                    'qty' => $d->qty,
                    'harga_satuan' => (int) $d->harga_satuan,
                    'total' => (int) $d->harga_satuan * $d->qty
                ];
            });

            return response()->json([
                'id_checkout' => $checkout->id_checkout,
                'tanggal' => $checkout->created_at->format('Y-m-d'),
                'via' => $checkout->via,
                'pelanggan' => [
                    'nama' => $checkout->pelanggan->nm_plg,
                    'alamat' => $checkout->pelanggan->alamat,
                    'email' => $checkout->pelanggan->email_plg
                ],
                'items' => $items,
                'subtotal' => $checkout->subtotal,
                'ongkir' => $checkout->ongkir,
                'diskon' => $checkout->diskon,
                'total_harga' => $checkout->total
            ]);
        }


    }
