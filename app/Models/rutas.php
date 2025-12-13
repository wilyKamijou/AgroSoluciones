<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rutas extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_ruta';
    protected $fillable = [
        'nombreR',
        'url'
    ];

    public function dtrutas()
    {
        return $this->hasMany(dtRuta::class, 'id_ruta');
    }
}
