<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class venta extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_venta';
    protected $fillable = [
        'fechaVe',
        'montoTotalVe',
        'id_cliente',
        'id_empleado'
    ];

    public function cliente()
    {
        return $this->belongsTo(cliente::class, 'id_cliente');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado');
    }

    public function detalleVentas()
    {
        return $this->belongsToMany(detalleVenta::class, 'id_venta');
    }
}
