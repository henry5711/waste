<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class rutas extends CrudModel
{
    protected $guarded = ['id'];
    protected $table = 'rutas';
    protected $fillable = ['cho_id','cho_name','fec_ruta','peso_total','peso_recibio','status'];
    public function Operaciones()
    {
        return $this->belongsToMany(operation::class,'detail_ruta','rut_id','ope_id');
    }
}