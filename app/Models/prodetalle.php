<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class prodetalle extends CrudModel
{
    protected $guarded = ['id'];
    protected $table = 'detallepro';
    protected $fillable = [
        'id_susp',
        'id_pro',
        'nom_pro',
        'precio',
        'cantidad',
        'impuesto',
        'sub_total',
        'descuento'
    ];

    /**
     * Get the Suscripcion that owns the prodetalle
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Suscripcion()
    {
        return $this->belongsTo(suscripciones::class, 'id_susp', 'id');
    }
}