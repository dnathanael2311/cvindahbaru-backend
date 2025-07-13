<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'id_brg';

    protected $fillable = [
        'img',
        'nm_brg',
        'id_ktg',
        'merk',
        'stok',
        'satuan_brg',
        'berat',
        'diskon',
        'harga_brg',
        'desk_brg'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_ktg', 'id_ktg');
    }

}
