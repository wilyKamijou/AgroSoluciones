<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_empleado';
    protected $fillable = [
        'nombreEm',
        'apellidosEm',
        'sueldoEm',
        'telefonoEm',
        'direccion',
        'id_tipoE',
        'user_id'
    ];


    public function tipoEmpleado()
    {
        return $this->belongsTo(tipoEmpleado::class, 'id_tipoE');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_empleado');
    }
}
