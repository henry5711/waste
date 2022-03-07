<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use App\Scopes\AddClientScope;
use App\Scopes\DeletedScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Branches extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'sucursal_suscripcion';
    protected $fillable = [
        'id',
        'sucursal_id',
        'suscripcion_id'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
    }

    /**
     * Get the suscripciones that owns the Branches
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sucripciones(): BelongsTo
    {
        return $this->belongsTo(suscripciones::class, 'suscripcion_id','id');
    }
}
