<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expedisi extends Model
{
    use HasFactory;

    protected $table = 'expedisi';
    protected $primaryKey = 'id_exp';

    protected $fillable = [
        'nm_exp',
        'no_exp',
        'email_exp'
    ];
}
