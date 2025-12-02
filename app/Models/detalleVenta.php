<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detalleVenta extends Model
{
    public $incrementing = false;
    protected $primaryKey = ['id_venta', 'id_producto', 'id_almacen'];
    public $timestamps = true;

    protected $fillable = [
        'cantidadDv',
        'precioDv',
        'id_venta',
        'id_producto',
        'id_almacen'
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta');
    }

    public function detalleAlmacen()
    {
        return $this->belongsToMany(DetalleAlmacen::class, ['id_producto', 'id_almacen']);
    }
    /*
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }*/
}
