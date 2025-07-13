<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Barangklr extends Model
{
    use HasFactory;

    protected $table = 'barangklr';
    protected $primaryKey = 'id_klr';

    protected $fillable = [
        'id_brg',
        'qty_klr',
        'tgl_klr',
        'desk_klr'
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'id_brg', 'id_brg');
    }
}
