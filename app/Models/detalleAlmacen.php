<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class detalleAlmacen extends Model
{
    use HasFactory;

    protected $primaryKey='idDal';

    protected $fillable = [
        'id_producto',
        'id_almacen',
        'stock'

    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'id_almacen');
    }

    public function detalleCompra()
    {
        return $this->hasMany(detalleCompra::class, 'idDc');
    }

    public function detalleVenta()
    {
        return $this->hasMany(detalleVenta::class, 'idDv');
    }
}
