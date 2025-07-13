<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangklrController;
use App\Http\Controllers\BarangmskController;
use App\Http\Controllers\DetailCheckoutController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ExpedisiController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DetailOrderController;
use App\Http\Controllers\IsiKeranjangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\DetailReturController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\RajaOngkirController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/posts/{id}', function ($id) {
    dd($id);

    return response('Post ' . $id);
})->where('id', '[0-9]+');

Route::get('/search', function (Request $request) {
    dd($request);
});

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'list_admin']);           // GET semua admin
    Route::post('/add', [AdminController::class, 'create'])->name('tambahadmin');           // POST tambah admin
    Route::get('/{id}', [AdminController::class, 'show'])->name('showadmin');              // GET detail admin
    Route::put('/update', [AdminController::class, 'update'])->name('updateadmin');        // PUT update admin
    Route::delete('/delete', [AdminController::class, 'delete'])->name('admin.delete');     // DELETE admin
    Route::post('/login', [AdminController::class, 'login'])->name('loginadmin');          // POST login
    Route::post('/password/forgot', [ForgotPasswordController::class, 'sendResetLink']);
});

Route::prefix('barang')->group(function () {
    Route::get('/', [BarangController::class, 'list_barang'])->name('barang');
    Route::get('/show', [BarangController::class, 'show'])->name('showbarang');
    Route::post('/add', [BarangController::class, 'create'])->name('tambahbarang');
    Route::post('/update', [BarangController::class, 'update'])->name('updatebarang');
    Route::post('/delete', [BarangController::class, 'delete'])->name('deletebarang');
});


Route::prefix('barangmsk')->group(function(){
    Route::get('/', [BarangmskController::class,'list_barangmsk'])->name('barangmsk');
    Route::post('/add', [BarangmskController::class,'create'])->name('tambahbarangmsk');
    Route::get('/get-all-barang', [BarangmskController::class,'getAllBarang']);
});

Route::prefix('barangklr')->group(function(){
    Route::get('/', [BarangklrController::class,'list_barangklr'])->name('barangklr');
    Route::post('/add', [BarangklrController::class,'create'])->name('tambahbarangklr');
    Route::get('/get-all-barang', [BarangmskController::class,'getAllBarang']);
});

Route::prefix('kategori')->group(function(){
    Route::get('/', [KategoriController::class, 'list_kategori']); // <- gunakan index()
    Route::post('/add', [KategoriController::class,'create'])->name('tambahkategori');
    Route::post('/update', [KategoriController::class, 'update'])->name('updatekategori');
    Route::post('/delete', [KategoriController::class, 'delete'])->name('deletekategori');
});


Route::prefix('expedisi')->group(function(){
    Route::get('/', [ExpedisiController::class,'list_expedisi'])->name('expedisi');
    Route::post('/add', [ExpedisiController::class,'create'])->name('tambahexpedisi');
    Route::post('/update', [ExpedisiController::class, 'update'])->name('updateexpedisi');
    Route::post('/delete', [ExpedisiController::class, 'delete'])->name('deleteexpedisi');
});

Route::prefix('pelanggan')->group(function(){
    Route::get('/', [PelangganController::class,'list_pelanggan']);
    Route::get('/{id}', [PelangganController::class,'show'])->name('showpelanggan');
    Route::post('/add', [PelangganController::class,'create'])->name('tambahpelanggan');
    Route::post('/update', [PelangganController::class, 'update'])->name('updatepelanggan');
    Route::post('/delete', [PelangganController::class, 'delete'])->name('deletepelanggan');
    Route::post('/login', [PelangganController::class, 'login']);
});


Route::prefix('keranjang')->group(function(){
    Route::get('/', [KeranjangController::class, 'list_keranjang'])->name('keranjang');
    Route::get('/get-by-pelanggan', [KeranjangController::class, 'getByPelanggan']);
});

Route::prefix('retur')->group(function () {
    Route::get('/', [ReturController::class, 'list_retur']);
    Route::post('/add', [ReturController::class, 'create']);
    Route::post('/update', [ReturController::class, 'update']);
    Route::post('/delete', [ReturController::class, 'delete']);
    
    Route::get('/show', [ReturController::class, 'show ']);
    Route::get('/by-orders', [ReturController::class, 'listByOrders']);
});


Route::prefix('detailretur')->group(function () {
    Route::get('/', [DetailReturController::class, 'list_detailretur']);
    Route::post('/add', [DetailReturController::class, 'create']);
    Route::get('/byretur', [DetailReturController::class, 'listByIdRetur']); // ✅ untuk halaman Detail Retur
});


Route::prefix('payment')->group(function(){
    Route::get('/', [PaymentController::class,'list_payment'])->name('payment');
    Route::post('/add', [PaymentController::class,'create'])->name('tambahpayment');
    Route::post('/midtrans/token', [PaymentController::class, 'getSnapToken']);

});

Route::prefix('isikeranjang')->group(function () {
    Route::get('/', [IsikeranjangController::class, 'list_isikeranjang'])->name('isikeranjang');
    Route::post('/add', [IsikeranjangController::class, 'create'])->name('tambahisikeranjang');
    Route::post('/update', [IsikeranjangController::class, 'update'])->name('updateisikeranjang');
    Route::post('/delete', [IsikeranjangController::class, 'delete'])->name('deleteisikeranjang');
    Route::get('/by-keranjang', [IsikeranjangController::class, 'getByKeranjang']);


    Route::get('/isi_keranjang', [IsiKeranjangController::class, 'index']);
});

Route::prefix('checkout')->group(function(){
    Route::get('/', [CheckoutController::class,'list_checkout'])->name('checkout');
    Route::post('/add', [CheckoutController::class,'create'])->name('tambahcheckout');
    Route::post('/update', [CheckoutController::class,'update'])->name('updatecheckout');
    Route::post('/delete', [CheckoutController::class, 'delete'])->name('deletecheckout');
    

    Route::get('/checkout/invoice', [CheckoutController::class, 'invoice']);
});

Route::prefix('order')->group(function(){
    Route::get('/', [OrderController::class,'list_order'])->name('order');
    Route::post('/add', [OrderController::class,'create'])->name('tambahorder');
    Route::post('/delete', [OrderController::class, 'delete'])->name('deleteorder');
    Route::get('/detail', [OrderController::class, 'getDetailOrder']);
    Route::get('/{id}', [OrderController::class, 'show']);

});

Route::prefix('detailcheckout')->group(function(){
    Route::get('/', [DetailcheckoutController::class,'list_detailcheckout'])->name('detailcheckout');
    Route::post('/add', [DetailcheckoutController::class,'create'])->name('tambahdetailcheckout');
    Route::post('/update', [DetailcheckoutController::class,'update'])->name('updatedetailcheckout');
    Route::post('/delete', [DetailcheckoutController::class,'delete'])->name('deletedetailcheckout');
});

Route::prefix('detailorder')->group(function(){
    Route::get('/', [DetailOrderController::class,'list_detailorder'])->name('detailorder');
    Route::post('/add', [DetailOrderController::class,'create'])->name('tambahdetailorder');
    Route::post('/delete', [DetailOrderController::class, 'delete'])->name('deletedetailorder');
});

Route::prefix('reward')->group(function () {
    Route::get('/', [DetailOrderController::class,'list_reward'])->name('detailorder');
    Route::post('/add', [RewardController::class, 'create'])->name('tambahreward');
    Route::post('/update', [RewardController::class, 'update'])->name('updatereward');
    Route::post('/delete', [RewardController::class, 'delete'])->name('deletereward');
});

Route::post('/forgot-password', [App\Http\Controllers\ForgotPasswordController::class, 'sendResetLink']);
Route::post('/reset-password', [App\Http\Controllers\ForgotPasswordController::class, 'reset']);

Route::post('/password/reset', [ForgotPasswordController::class, 'reset']);
Route::get('/rajaongkir/provinces', [RajaOngkirController::class,'provinces']);
Route::get('/rajaongkir/cities/{provinceId}', [RajaOngkirController::class, 'cities']);
Route::get('/rajaongkir/cost', [RajaOngkirController::class,'cost']);

use App\Models\Order;
Route::get('/debug-orders', function () {
    return \App\Models\Order::with('pelanggan')->get();
});
