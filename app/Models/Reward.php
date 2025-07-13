<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reward extends Model
{
    use HasFactory;

    protected $table = "reward";
    protected $primaryKey = "id_reward";

    protected $fillable = [
        'id_plg',
        'jenis_rwd',
        'value_rwd',
        'expired_rwd'
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_plg', 'id_plg');
    }
}
