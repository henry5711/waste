<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class suscripciones extends CrudModel
{
    protected $guarded = ['id'];
    protected $table = 'suscripciones';
    protected $fillable = ['numero','id_sus','correo','nombre','fec_ini','fec_fin','ciclo','sta'];
}