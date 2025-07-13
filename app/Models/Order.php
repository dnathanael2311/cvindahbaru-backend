<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = "order";
    protected $primaryKey = "id_order";
    public $timestamps = false;

    protected $fillable = [
        'id_plg',
        'id_checkout'
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_plg', 'id_plg');
    }

    public function checkout()
    {
        return $this->belongsTo(\App\Models\Checkout::class, 'id_checkout');
    }
    public function detailorder(): HasMany
    {
        return $this->hasMany(DetailOrder::class, 'id_order', 'id_order');
    }
    
}


