<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Clientes extends CrudModel
{
    protected $guarded = ['id'];
    protected $table = 'clientes_suscripcion';
    protected $fillable = [
        'id_suscripcion',
        'id_client',
        'nombre',
        'correo_ruc'
    ];
    private $nomb;
    public function scopeFiltro($query,$request){
        return $query
                    ->where('id_suscripcion',$request->id_suscripcion)
                    ->when($request->id_client,function($query,$id_client){
                        return $query->where('id_client','=',$id_client);
                    })
                    ->when($request->nombre,function($query,$nombre){
                        $this->nomb = $nombre;
                        return $query->where(function($query){
                            return $query->where('nombre','ilike',"%".$this->nomb."%")
                                        ->orwhere('correo_ruc','ilike',"%".$this->nomb."%");
                        });
                    });
    }
    /**
     * Get the Suscripcion that owns the Clientes
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Suscripcion()
    {
        return $this->belongsTo(suscripciones::class, 'id_suscripcion', 'id');
    }
}