<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class login extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario',
        'contraseña'
    ];

    public function empleados()
    {
        return $this->hasOne(Producto::class, 'id_categoria');
    }
}
