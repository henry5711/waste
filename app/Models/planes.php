<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class planes extends CrudModel
{
    protected $guarded = ['id'];
    protected $table = 'planes';
    protected $fillable = ['plan','precio','Periodicidad','condi','obs','icon','tipo'];
}