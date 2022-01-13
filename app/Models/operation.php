<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class operation extends CrudModel
{
    protected $guarded = ['id'];
    protected $table='operation';
    protected $fillable = ['name_sucursal','coordenada','fecha_ope','obs','tipo','peso','status'];
}