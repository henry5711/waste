<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Log;

class AccountScope implements Scope
{
        private $request;
        public function __construct()
        {
            $this->request = app('Illuminate\Http\Request');
        }

    public function apply(Builder $builder, Model $model)
    {
        $account = null;
        if ($this->request->get('user')){
            $account = $this->request->get('user')->user->current_account;
        } elseif ($this->request->has('account')){
            $account = intval($this->request->input('account'));
        } elseif ($this->request->hasHeader('x-account')){
            $account = intval($this->request->header('x-account'));
        }

        if ($account){
            $builder->where($model->getTable() . '.msa_account',"=",intval($account));
        }
    }
}