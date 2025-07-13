<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Retur extends Model
{
    use HasFactory;

    protected $table = "retur";
    protected $primaryKey = "id_rt";

    protected $fillable = [
        'id_order',
        'tgl_rt',
        'st_retur'
    ];

    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class, 'id_order');
    }
}
