<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Clientes extends CrudModel
{
    protected $guarded = ['id'];
    protected $table = 'clientes_suscripcion';
    protected $fillable = [
        'id',
        'id_suscripcion',
        'id_client',
        'nombre',
        'correo_ruc'
    ];

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