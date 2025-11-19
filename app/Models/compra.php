<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class compra extends Model
{
    use HasFactory;
    protected $primaryKey='id_compra';
    protected $fillable = [
        'fechaCo',
        'montoTotalCo',
        'id_empleado',
        'id_proveedor'
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor');
    }

    public function detalleCompras()
    {
        return $this->hasMany(DetalleCompra::class, 'id_compra');
    }
}
