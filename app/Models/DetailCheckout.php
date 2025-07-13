<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailCheckout extends Model
{
    use HasFactory;

    protected $table = 'detailcheckout';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'id_checkout',
        'id_brg',
        'dt_hargasatuan',
        'dt_qty'
    ];

    public function checkout(): BelongsTo
    {
        return $this->belongsTo(Checkout::class, 'id_checkout', 'id_checkout');
    }

    public function barang()
{
    return $this->belongsTo(Barang::class, 'id_brg', 'id_brg');
}

}
