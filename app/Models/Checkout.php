<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Checkout extends Model
{
    use HasFactory;

    protected $table = 'checkout';
    protected $primaryKey = 'id_checkout';

    protected $fillable = [
        'id_krg',
        'id_plg',
        'id_rwd',
        'ongkir',
        'ttl_harga',
        'tgl_checkout',
        'kurir'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_plg', 'id_plg');
    }

    public function keranjang(): BelongsTo
    {
        return $this->belongsTo(Keranjang::class, 'id_krg', 'id_krg');
    }

    public function reward(): BelongsTo
    {
        return $this->belongsTo(Reward::class, 'id_rwd', 'id_rwd');
    }
    public function detail()
{
    return $this->hasMany(DetailCheckout::class, 'id_checkout', 'id_checkout');
}

}
