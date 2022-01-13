<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class suscripciones extends CrudModel
{
    protected $guarded = ['id'];
    protected $table = 'suscripciones';
    protected $fillable = [
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
        'obs',
        'total'
    ];
    

    public function scopeFiltro($query,$request){
        return $query
                    ->when($request->estado,function($query,$estado){
                        return $query->where('sta','=',$estado);
                    })
                    ->when($request->numero,function($query,$numero){
                        return $query->where('numero','ilike',"%$numero%");
                    });
    }

    /**
     * Get all of the Productos for the suscripciones
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Productos()
    {
        return $this->hasMany(prodetalle::class, 'id_susp', 'id');
    }

    /**
     * Get all of the Clientes for the suscripciones
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Clientes()
    {
        return $this->hasMany(Clientes::class, 'id_suscripcion', 'id');
    }
}