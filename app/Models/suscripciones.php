<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use Carbon\Carbon;

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
        'total',
        'ico',
        'titulo'
    ];
    

    public function scopeFiltro($query,$request){
        $bool = false;
        if($request->fecha_ini != null && $request->fecha_ini != '' && $request->fecha_fin != null && $request->fecha_fin != ''){
            $bool = true;
        }
        return $query
                    ->when($request->estado,function($query,$estado){
                        if($estado == 'Facturar'){
                            return $query   ->where( 'sta','Activa')
                                            ->whereTime( 'prox_cob','<',Carbon::now());
                        }else{
                            return $query->where('sta','=',$estado);
                        }
                    })
                    ->when($request->numero,function($query,$numero){
                        return $query->where('numero','ilike',"%$numero%");
                    })
                    ->when($bool,function($query) use ($request){
                        return $query->whereBetween('prox_cob',[$request->fecha_ini,$request->fecha_fin]);
                    })
                    ->OrderBy('id','desc');
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