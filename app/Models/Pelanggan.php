<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = "pelanggan";
    protected $primaryKey = "id_plg";

    protected $fillable = [
        'user_plg',
        'nm_plg',
        'no_plg',
        'email_plg',
        'tgl_lahir',
        'alamat',
        'provinsi',
        'kota',  // ⬅️ tambahkan ini
        'pass_plg'
    ];


    public function keranjang(): HasOne
    {
        return $this->hasOne(Keranjang::class, 'id_plg', 'id_plg');
    }
}
