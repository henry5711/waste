<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class config extends CrudModel
{
    protected $guarded = ['id'];
    protected $table='config';
    protected $fillable = ['pt_ini_cfg','pt_fin_cfg'];
}