<?php

namespace App\Http\Controllers\rutas;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\operation;
use App\Models\rutas;
use App\Services\rutas\rutasService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/** @property rutasService $service */
class rutasController extends CrudController
{
    public function __construct(rutasService $service)
    {
        parent::__construct($service);
    }

    public function showrut($id)
    {
      
        $ruta=rutas::with('Operaciones')->find($id);
        $ruta->peso_total=$ruta->operaciones->sum('peso');
        $estado=$ruta->operaciones->where('status','En ruta');
        
        if(count($estado)==0)
        {
            $r=rutas::find($id);
            $r->status='TERMINADA';
            $r->save();
        }
        

        
        return $ruta;
    }

    public function filtro(Request $request)
    {
        $op=rutas::with('Operaciones')
        ->when($request->date, function($query, $interval){
            $date = explode('_', $interval);
            $date[0] = Carbon::parse($date[0])->format('Y-m-d');
            $date[1] = Carbon::parse($date[1])->format('Y-m-d');
            return $query->whereBetween(
                DB::raw("TO_CHAR(fec_ruta,'YYYY-MM-DD')"),[$date[0],$date[1]]);
            })
        ->when($request->cod,function($query,$code){
            //buscar sucursal o usuario
            return $query->where('cod_rut','ILIKE',"%$code%");
        })
        ->when($request->chofer,function($query,$chofer){
            //buscar sucursal o usuario
            return $query->where('cho_name','ILIKE',"%$chofer%");
        })
        ->when($request->sta,function($query,$sta){
            //buscar sucursal o usuario
            return $query->where('status','ILIKE',"%$sta%");
        })
        ->when($request->name,function($query,$name){
            //buscar sucursal o usuario
            return $query->whereHas('operaciones', function (Builder $query) use ($name) {

                $query->where('operaciones.name_sucursal','ILIKE',"%$name%");
            });
        })->get();

        return ["list"=>$op,"total"=>count($op)];
    }

    public function  repofil(Request $request)
    {
        $op=rutas::with('Operaciones')
        ->when($request->date, function($query, $interval){
            $date = explode('_', $interval);
            $date[0] = Carbon::parse($date[0])->format('Y-m-d');
            $date[1] = Carbon::parse($date[1])->format('Y-m-d');
            return $query->whereBetween(
                DB::raw("TO_CHAR(fec_ruta,'YYYY-MM-DD')"),[$date[0],$date[1]]);
            })
        ->when($request->cod,function($query,$code){
            //buscar sucursal o usuario
            return $query->where('cod_rut','ILIKE',"%$code%");
        })
        ->when($request->chofer,function($query,$chofer){
            //buscar sucursal o usuario
            return $query->where('cho_name','ILIKE',"%$chofer%");
        })
        ->when($request->sta,function($query,$sta){
            //buscar sucursal o usuario
            return $query->where('status','ILIKE',"%$sta%");
        })
        ->when($request->name,function($query,$name){
            //buscar sucursal o usuario
            return $query->whereHas('operaciones.name_sucursal','ILIKE',"%$name%");
        })->get();


        foreach ($op as $key) 
        {
         $key->peso_to=$key->operaciones->sum('peso');
        }


             //aqui se crea el excel
       $archivo=new Spreadsheet();
       //aqui la hoja
      $hoja=$archivo->getActiveSheet();
      $hoja->setTitle("rutas");

      $hoja->mergeCells('A1:J1');
      $hoja->setCellValue('A1','REPORTE DE RUTAS');

      //ancho de las celdas
      $archivo->getActiveSheet()->getColumnDimension('A')->setWidth(270, 'px');
      $archivo->getActiveSheet()->getColumnDimension('B')->setWidth(220, 'px');
      $archivo->getActiveSheet()->getColumnDimension('C')->setWidth(220, 'px');
      $archivo->getActiveSheet()->getColumnDimension('D')->setWidth(270, 'px');
      $archivo->getActiveSheet()->getColumnDimension('E')->setWidth(220, 'px');
      $archivo->getActiveSheet()->getColumnDimension('F')->setWidth(220, 'px');
      $archivo->getActiveSheet()->getColumnDimension('G')->setWidth(220, 'px');
      $archivo->getActiveSheet()->getColumnDimension('H')->setWidth(300, 'px');
      $archivo->getActiveSheet()->getColumnDimension('I')->setWidth(300, 'px');
      $archivo->getActiveSheet()->getColumnDimension('J')->setWidth(220, 'px');

        //AQUI CENTRO LOS TITULOS
        $archivo->getActiveSheet()->getStyle('A:J')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        //COLOR  al primer cuadro
        $archivo->getActiveSheet()->getStyle('A3:J3')->getFill()
           ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
           ->getStartColor()->setRGB('416DA9');

        //titulos
        $hoja->setCellValue('A3','ID');
        $hoja->setCellValue('B3','NUMERO DE CHOFER');
        $hoja->setCellValue('C3','NOMBRE DE CHOFER');
        $hoja->setCellValue('D3','FECHA DE RUTA');
        $hoja->setCellValue('E3','PESO RECIBIDO');
        $hoja->setCellValue('F3','PESO TOTAL');
        $hoja->setCellValue('G3','CODIGO');
        $hoja->setCellValue('H3','PUNTO DE INICIO');
        $hoja->setCellValue('I3','PUNTO DE FIN');
        $hoja->setCellValue('J3','ESTATUS');

       
        //TAMAÑO DEL TITULO
        $archivo->getActiveSheet()->getStyle('A3:J3')->getFont()
        ->applyFromArray( [ 'name' => 'Arial', 'bold' => TRUE, 'italic' => FALSE,'strikethrough' => FALSE,'size'=>12, 'color' => [ 'rgb' => 'ffffff' ] ] );

        $fila=4;

        foreach ($op as $key)
        {
            $hoja->setCellValue('A'.$fila,$key->id);
            $hoja->setCellValue('B'.$fila,$key->cho_id);
            $hoja->setCellValue('C'.$fila,$key->cho_name);
            $hoja->setCellValue('D'.$fila,$key->fec_ruta);
            $hoja->setCellValue('E'.$fila,$key->peso_recibio);
            $hoja->setCellValue('F'.$fila,$key->peso_to);
            $hoja->setCellValue('G'.$fila,$key->cod_rut);
            $hoja->setCellValue('H'.$fila,$key->pt_ini);
            $hoja->setCellValue('I'.$fila,$key->pt_fin);
            $hoja->setCellValue('J'.$fila,$key->status);
            $fila++;
        }

        
             //aqui para descargar excel
             header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
             header('Content-Disposition: attachment; filename="Reporte filtro rutas.xlsx"');
             $writer=IOFactory::createWriter($archivo,'Xlsx');
             $writer->save("php://output");
             exit;
    }
}