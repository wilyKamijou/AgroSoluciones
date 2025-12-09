<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class detalleAlmacen extends Model
{
    public $incrementing = false;
    protected $primaryKey = ['id_producto', 'id_almacen'];
    public $timestamps = true;

    protected $fillable = [
        'id_producto',
        'id_almacen',
        'stock'

    ];

    public function producto()
    {
        return $this->belongsTo(producto::class, 'id_producto');
    }

    public function almacen()
    {
        return $this->belongsTo(almacen::class, 'id_almacen');
    }
    /*
    public function detalleCompra()
    {
        return $this->hasMany(detalleCompra::class, 'idDc');
    }
*/
    public function detalleVenta()
    {
        return $this->belongsToMany(detalleVenta::class, ['id_producto', 'id_almacen']);
    }
}
