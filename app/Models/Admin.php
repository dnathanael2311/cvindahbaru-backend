<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admin';
    protected $primaryKey = 'id_adm';

    protected $fillable = [
        'nm_adm',
        'user_adm',
        'no_adm',
        'email_adm',
        'pass_adm',
    ];
}
