<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Acceso extends CrudModel
{
    protected $guarded = ['id'];
    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    /**
     * The planes that belong to the Acceso
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function planes(): BelongsToMany
    {
        return $this->belongsToMany(planes::class, 'acceso_plan', 'plan_id', 'acceso_id');
    }
}