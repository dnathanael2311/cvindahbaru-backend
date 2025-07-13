<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IsiKeranjang extends Model
{
    protected $table = 'isikeranjang';

    public $incrementing = false;
    protected $primaryKey = null;
    public $timestamps = true;
    

    protected $fillable = [
        'id_krg',
        'id_brg',
        'krg_qty'
    ];

    public function keranjang()
    {
        return $this->belongsTo(Keranjang::class, 'id_krg', 'id_krg');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_brg', 'id_brg');
    }

}
