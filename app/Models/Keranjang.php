<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Keranjang extends Model
{
    use HasFactory;

    protected $table = "keranjang";
    protected $primaryKey = "id_krg";

    protected $fillable = [
        'id_plg'
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_plg', 'id_plg');
    }

    public function isikeranjang(): HasMany
    {
        return $this->hasMany(IsiKeranjang::class, 'id_krg', 'id_krg');
    }
}
