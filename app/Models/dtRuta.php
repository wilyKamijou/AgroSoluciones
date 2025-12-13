<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dtRuta extends Model
{
    public $incrementing = false;
    protected $primaryKey = ['id_tipoE', 'id_ruta'];
    public $timestamps = true;

    protected $fillable = [
        'id_tipoE',
        'id_ruta'
    ];

    public function tipo_empledos()
    {
        return $this->belongsTo(tipoEmpleado::class, 'id_tipoE');
    }
    public function rutas()
    {
        return $this->belongsTo(rutas::class, 'id_ruta');
    }
}
