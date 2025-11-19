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
        'puestoEm',
        'telefonoEm',
        'direccion',
        'id_tipoE',
        'usuario',  // Campo para el usuario relacionado con la tabla login
        'contraseña'  // Campo para la contraseña correspondiente
    ];


    public function tipoEmpleado()
    {
        return $this->belongsTo(TipoEmpleado::class, 'id_tipoE');
    }

    /**
     * Relación con el modelo Login.
     * Aquí aseguramos que cada empleado tenga una única entrada en login
     */
    public function users()
    {
        return $this->hasOne(user::class, 'id'); 
    }

    public function logins()
    {
        return $this->hasOne(user::class, 'usuario'); 
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_empleado');
    }

    public function compras()
    {
        return $this->hasMany(Compra::class, 'id_empleado');
    }
}

