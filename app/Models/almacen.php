<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class almacen extends Model
{
    use HasFactory;
    protected $primaryKey='id_almacen';
    protected $fillable = [
        'nombreAl',
        'descripcionAl',
        'direccionAl'
    ];

    public function detalleAlmacenes()
    {
        return $this->hasMany(DetalleAlmacen::class, 'id_almacen');
    }
}
