<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipoEmpleado extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_tipoE';
    protected $fillable = [
        'nombreE',
        'descripcionTip'
    ];

    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'id_tipoE');
    }
    public function dtrutas()
    {
        return $this->hasMany(dtRuta::class, 'id_tipoE');
    }
}
