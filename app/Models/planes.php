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