<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $table = "payment";
    protected $primaryKey = "id_payment";

    protected $fillable = [
        'id_checkout',
        'transaction_time',
        'transaction_status',
        'pdf_url'
    ];

    public function checkout(): BelongsTo
    {
        return $this->belongsTo(Checkout::class, 'id_checkout', 'id_checkout');
    }
}
