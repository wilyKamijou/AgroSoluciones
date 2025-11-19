<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class producto extends Model
{
    use HasFactory;
    protected $primaryKey='id_producto';
    protected $fillable = [
        'nombrePr',
        'nombreTecnico',
        'descripcionPr',
        'compocicionQuimica',
        'consentracionQuimica',
        'fechaFabricacion',
        'fechaVencimiento',
        'unidadMedida',
        'id_categoria'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function detalleAlmacenes()
    {
        return $this->hasMany(DetalleAlmacen::class, 'id_producto');
    }
/*
    public function detalleCompras()
    {
        return $this->hasMany(DetalleCompra::class, 'id_producto');
    }

    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class, 'id_producto');
    }*/
}
