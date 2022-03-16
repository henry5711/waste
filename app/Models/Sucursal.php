<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use App\Scopes\AddClientScope;
use App\Scopes\DeletedScope;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sucursal extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'branches';
    /*    protected $connection = 'onlyRead'; */
    protected $fillable = [
        'id',
        'client_id',
        'msa_account',
        'code',
        'name',
        'address',
        'coordinate',
        'image',
        'phones',
        'status',
        'sector_id',
        'manager',
        'deleted'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DeletedScope);
        static::addGlobalScope(new AddClientScope);
    }
    public function scopeFiltro($query, $request)
    {
        return $query
            ->when($request->id, function ($query, $id) {
                return $query->where('id', '=', $id);
            })
            ->when($request->client_id, function ($query, $client_id) {
                return $query->where('client_id', '=', "$client_id");
            })
            ->when($request->name, function ($query, $name) {
                return $query->where(function ($query) use ($name) {
                    $query->where('name', 'ilike', "%$name%")
                        ->orWhere('code', 'ilike', "%$name%");
                });
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('status', '=', "$status");
            })
            ->when($request->manager, function ($query, $manager) {
                return $query->where('manager', '=', "$manager");
            })
            ->OrderBy('id');
    }

    /**
     * The Suscripciones that belong to the Sucursal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function Suscripciones(): BelongsToMany
    {
        return $this->belongsToMany(suscripciones::class, 'sucursal_suscripcion', 'suscripcion_id', 'sucursal_id');
    }
}
