<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialBillingMasive extends Model
{
    use HasFactory;
    protected $fillable = [
        'expected_quantity',
        'real_quantity',
        'suscripcion_id',
        'status' // 'Finalizada','Error','En Proceso'
    ];

    /**
     * Get the suscripcion that owns the HistorialBillingMasive
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function suscripcion()
    {
        return $this->belongsTo(suscripciones::class, 'suscripcion_id');
    }
}
