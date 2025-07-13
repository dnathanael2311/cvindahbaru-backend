<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Barangmsk extends Model
{
    use HasFactory;

    protected $table = 'barangmsk';
    protected $primaryKey = 'id_msk';

    protected $fillable = [
        'id_brg',
        'id_exp',
        'qty_msk',
        'tgl_msk',
        'desk_msk'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_brg');
    }

    public function expedisi()
    {
        return $this->belongsTo(Expedisi::class, 'id_exp');
    }
}
