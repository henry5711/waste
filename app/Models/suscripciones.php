<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class suscripciones extends CrudModel
{
    protected $guarded = ['id'];
    protected $table = 'suscripciones';
    protected $fillable = [
        'id',
        'numero',
        'id_client',
        'correo',
        'nombre',
        'fec_ini',
        'fec_fin',
        'sta',
        'prox_cob',
        'periodo',
        'base_ip',
        'impuesto',
        'obs'
    ];
    
    /**
     * Get all of the Productos for the suscripciones
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Productos()
    {
        return $this->hasMany(prodetalle::class, 'id_susp', 'id');
    }
}