<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 * Date: 8/1/18
 * Time: 11:24 AM
 */

namespace App\Core;


use App\Query\QueryBuilder;
use Illuminate\Database\Eloquent\Model;

class CrudModel extends Model
{
    protected $images;

    protected $fillable = ['*'];

    protected $hidden   = ["updated_at","created_at"];

    public function scopeDoWhere($query, $request) {


        $list = QueryBuilder::for(static::class)
            ->select($this->getColumns($request))
            ->doJoin($this->getJoins($request))
            ->doWhere($this->getWhere($request))
            ->sort($this->getSort($request));
            //->paginate($this->getPag($request));

        return $list;
    }


    protected static function boot()
    {
        parent::boot();
    }

    public function getPag($request)
    {
        $paginate = 10;
        if(isset($_GET['paginate']))
        {
            $paginate = $request->paginate;
        }
        return $paginate;
    }

    public function getJoins($request)
    {
        $joins = null;
        if(isset($_GET['join']))
        {
            $joins = $request->join;
        }
        return $joins;
    }

    public function getSort($request)
    {
        $sort = null;
        if(isset($_GET['sort']))
        {
            $sort = $request->sort;
        }
        return $sort;
    }

    public function getColumns($request)
    {
        $columns = ['*'];
        if(isset($_GET['columns']))
        {
            $c = json_decode($request->columns);
            $columns = $c;
        }

        return $columns;
    }

    public function getWhere($request)
    {
        $where = null;
        if(isset($_GET['where']))
        {

            $where = $request->where;
        }

        return $where;
    }

}