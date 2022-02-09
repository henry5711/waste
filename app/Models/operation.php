<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class operation extends CrudModel
{
    protected $guarded = ['id'];
    protected $table='operation';
    protected $fillable = ['ids','name_sucursal','coordenada','fecha_ope','obs','tipo','peso','status','fecha','tlf','ref','usu/cli'];

    public function Rutas()
    {
        return $this->belongsToMany(rutas::class,'detail_ruta','ope_id','rut_id');
    }
}