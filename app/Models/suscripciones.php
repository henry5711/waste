<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'titulo',
        'prox_operation',
        'sucursal_id'
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
                                            ->whereTime( 'prox_cob','<',Carbon::now()->endOfDay());
                        }elseif($estado == 'Operaciones'){
                            return $query   ->where( 'sta','Activa')
                                            ->whereTime( 'prox_operation','<',Carbon::now()->endOfDay());
                        }
                        else{
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
     * Encuentra todas las suscripciones que faltan por facturar
     *
     */
    public function scopeFacturar($query){
        $query  ->where( 'sta','Activa')
                ->whereTime( 'prox_cob','<',Carbon::now()->endOfDay());
    }

    /**
     * Obtiene todas las suscripciones que hacen falta por generar operaciones
     *
     */
    public function scopeOperations($query){
        $query  ->where( 'sta','Activa')
                ->whereTime( 'prox_operation','<',Carbon::now()->endOfDay())
                ->whereHas('clientes',function(Builder $q2){
                    $q2 ->whereNotNull('coordenada_sucursal')
                        ->where('coordenada_sucursal','!=','');
                })
                ;
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

    public function Sucursales(){
        return $this->hasMany(Sucursal::class,'suscripcion_id');
    }

    /**
     * Get the branches that owns the suscripciones
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function branches()
    {
        return $this->hasMany(Branches::class,'suscripcion_id');
    }

    /**
     * Get all of the historialBillingMasive for the suscripciones
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function historialBillingMasive()
    {
        return $this->hasMany(HistorialBillingMasive::class, 'suscripcion_id');
    }
}