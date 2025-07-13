<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailOrder extends Model
{
    use HasFactory;

    protected $table = "detailorder";

    protected $fillable = [
        'id_order',
        'id_brg',
        'dor_qty',
        'dor_hargasatuan'
    ];

    public function historitransaksi(): BelongsTo{
        return $this->belongsto(HistoriTransaksi::class, 'id_order', 'id_order');
    }

    public function barang()
{
    return $this->belongsTo(Barang::class, 'id_brg', 'id_brg');
}

}
