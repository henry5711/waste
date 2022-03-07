<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AddClientScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->select([
            'branches.*',
            'clients.commerce_name as nombre_cliente',
            'clients.rif as rif_cliente',
        ])
        ->leftJoin('clients','clients.id','=','branches.client_id')
        ;
//        $builder->where($model->getTable() . '.deleted', '=', false);
    }
}