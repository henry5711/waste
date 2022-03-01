<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class operation extends CrudModel
{
    protected $guarded = ['id'];
    protected $table='operation';
    protected $fillable = [
        'ids', // id del sucursal / usuario
        'name_sucursal',
        'coordenada', 
        'fecha_ope', // fecha en curso
        'obs', // opcional
        'tipo', // web (siempre)
        'peso', // null
        'status', // Creada
        'fecha', // 
        'tlf', // op
        'ref', // op
        'usu/cli']; // cliente

    public function Rutas()
    {
        return $this->belongsToMany(rutas::class,'detail_ruta','ope_id','rut_id');
    }
}