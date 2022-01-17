<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class operation extends CrudModel
{
    protected $guarded = ['id'];
    protected $table='operation';
    protected $fillable = ['name_sucursal','coordenada','fecha_ope','obs','tipo','peso','status'];

    public function Rutas()
    {
        return $this->belongsToMany(rutas::class,'detail_ruta','ope_id','rut_id');
    }
}