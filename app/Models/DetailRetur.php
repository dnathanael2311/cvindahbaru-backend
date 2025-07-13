<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailRetur extends Model
{
    use HasFactory;

    protected $table = "detailretur";
    protected $primaryKey = null; // karena pakai composite key
    public $incrementing = false;

    protected $fillable = [
        'id_rt',
        'id_brg',
        'qty_rt',
        'alasan'
    ];

    public function retur(): BelongsTo {
        return $this->belongsTo(Retur::class, 'id_rt', 'id_rt');
    }

    public function barang(): BelongsTo {
        return $this->belongsTo(Barang::class, 'id_brg', 'id_brg');
    }
}
