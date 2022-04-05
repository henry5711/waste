<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class planes extends CrudModel
{
    protected $guarded = ['id'];
    protected $table = 'planes';
    protected $fillable = [
        'plan',
        'precio',
        'Periodicidad',
        'id_propietario',
        'obs',
        'icon',
        'propietario'
    ];

    public function scopeFiltro($query, $request)
    {
        return $query->when($request->plan, function ($query2, $plan) {
            return $query2->where('plan', 'ilike', "%$plan%");
        })
            ->when($request->id_propietario, function ($query2, $id_propietario) {
                return $query2->where('id_propietario', $id_propietario);
            })
            ->when($request->propietario, function ($query2, $propietario) {
                return $query2->where('propietario', 'ilike', "%$propietario%");
            })
            ->when($request->Periodicidad, function ($query2, $periodo) {
                return $query2->where('Periodicidad', $periodo);
            });
    }
    /**
     * The accesos that belong to the planes
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function accesos(): BelongsToMany
    {
        return $this->belongsToMany(Acceso::class, 'acceso_plan', 'acceso_id', 'plan_id');
    }
}