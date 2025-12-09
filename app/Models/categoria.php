<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categoria extends Model
{
    use HasFactory;
    protected $primaryKey='id_categoria';
    protected $fillable = [
        'nombreCat',
        'descripcionCat'
    ];

    public function productos()
    {
        return $this->hasMany(producto::class, 'id_categoria');
    }
}
