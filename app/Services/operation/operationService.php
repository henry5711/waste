<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\operation;


use App\Core\CrudService;
use App\Http\Mesh\ClientService;
use App\Models\operation;
use App\Repositories\operation\operationRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** @property operationRepository $repository */
class operationService extends CrudService
{

    protected $name = "operation";
    protected $namePlural = "operations";

    public function __construct(operationRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _store(Request $request)
    {
        $request['fecha_ope']=Carbon::now();
        $request['status']='Creada';
        return parent::_store($request);
    }

    public function topFour(Request $request)
    {
        $top = DB::select(
            DB::raw('
            select * from (
                select
                    o.name_sucursal,
                    o.ids,
                    sum(o.peso) peso
                    from operation o
                    where "usu/cli" = \'cliente\'
                    group by (name_sucursal,ids)
            ) t
            where t.peso is not null
            order by t.peso desc
            limit(4)')
        );
        
        $ids = [];
        foreach($top as $item){
            $ids[] = $item->ids;
        }
        
        $query = [
            'ids' => $ids
        ];

        $branches = collect((new ClientService)->getFilterBranches($query));

        foreach($top as $item){
            
            $client = $branches->where('id',$item->ids)->first();
            $item->client = $client != null ? $client['client'] : null;
        }
        
        return $top;
 
    }
    
}