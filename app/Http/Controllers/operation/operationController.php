<?php

namespace App\Http\Controllers\operation;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\operation;
use App\Services\operation\operationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/** @property operationService $service */
class operationController extends CrudController
{
    public function __construct(operationService $service)
    {
        parent::__construct($service);
    }

    public function icreadas()
    {
        $ope=operation::where('status','Creada')->get();
        return  ["list"=>$ope,"total"=>count($ope)];
    }

    public function filtro(Request $request)
    {
        $op=operation::when($request->date, function($query, $interval){
            $date = explode('_', $interval);
            $date[0] = Carbon::parse($date[0])->format('Y-m-d');
            $date[1] = Carbon::parse($date[1])->format('Y-m-d');
            return $query->whereBetween(
                DB::raw("TO_CHAR(fecha,'YYYY-MM-DD')"),[$date[0],$date[1]]);
            })
        ->when($request->name,function($query,$name){
            //buscar sucursal o usuario
            return $query->where('name_sucursal','ILIKE',"%$name%");
        })->get();

        return ["list"=>$op,"total"=>count($op)];
    }

    public function reportope(Request $request)
    {
        $op=operation::when($request->date, function($query, $interval){
            $date = explode('_', $interval);
            $date[0] = Carbon::parse($date[0])->format('Y-m-d');
            $date[1] = Carbon::parse($date[1])->format('Y-m-d');
            return $query->whereBetween(
                DB::raw("TO_CHAR(fecha,'YYYY-MM-DD')"),[$date[0],$date[1]]);
            })
        ->when($request->name,function($query,$name){
            //buscar sucursal o usuario
            return $query->where('name_sucursal','ILIKE',"%$name%");
        })->get();

     
        if($request->date==0)
        {
            $date='reporte completo';
        }
        else
        {
        $date = explode('_',$request->date);
        $date[0] = Carbon::parse($date[0])->format('Y-m-d');
        $date[1] = Carbon::parse($date[1])->format('Y-m-d');
        }

         //aqui se crea el excel
       $archivo=new Spreadsheet();
       //aqui la hoja
      $hoja=$archivo->getActiveSheet();
      $hoja->setTitle("Operaciones");

      $hoja->mergeCells('A1:L1');
      $hoja->mergeCells('A2:L2');
      $hoja->setCellValue('A1','OPERACIONES DE WASTE');
       if($request->date==0)
        {
            $hoja->setCellValue('A2',"$date");
        }
        else
        {
            $hoja->setCellValue('A2',"DEL  $date[0] AL $date[1] ");
        }
    
      
       //ancho de las celdas
       $archivo->getActiveSheet()->getColumnDimension('A')->setWidth(220, 'px');
       $archivo->getActiveSheet()->getColumnDimension('B')->setWidth(220, 'px');
       $archivo->getActiveSheet()->getColumnDimension('C')->setWidth(220, 'px');
       $archivo->getActiveSheet()->getColumnDimension('D')->setWidth(220, 'px');
       $archivo->getActiveSheet()->getColumnDimension('E')->setWidth(220, 'px');
       $archivo->getActiveSheet()->getColumnDimension('F')->setWidth(220, 'px');
       $archivo->getActiveSheet()->getColumnDimension('G')->setWidth(220, 'px');
       $archivo->getActiveSheet()->getColumnDimension('H')->setWidth(220, 'px');
       $archivo->getActiveSheet()->getColumnDimension('I')->setWidth(220, 'px');
       $archivo->getActiveSheet()->getColumnDimension('J')->setWidth(220, 'px');
       $archivo->getActiveSheet()->getColumnDimension('K')->setWidth(220, 'px');
       $archivo->getActiveSheet()->getColumnDimension('L')->setWidth(220, 'px');

         //AQUI CENTRO LOS TITULOS
         $archivo->getActiveSheet()->getStyle('A:L')
         ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
         //COLOR  al primer cuadro
         $archivo->getActiveSheet()->getStyle('A4:L4')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('416DA9');

         //titulos
         $hoja->setCellValue('A4','ID');
         $hoja->setCellValue('B4','ID CLIENTE O USUARIO');
         $hoja->setCellValue('C4','NOMBRE SUCURSAL/USUARIO');
         $hoja->setCellValue('D4','COORDENADA');
         $hoja->setCellValue('E4','FECHA DE OPERACION');
         $hoja->setCellValue('F4','FECHA DE REGISTRO');
         $hoja->setCellValue('G4','USUARIO/CLIENTE');
         $hoja->setCellValue('H4','PESO');
         $hoja->setCellValue('I4','TELEFONO');
         $hoja->setCellValue('J4','WEB/APP');
         $hoja->setCellValue('K4','REFERENCIA');
         $hoja->setCellValue('L4','ESTADO');
        
         //TAMAÑO DEL TITULO
         $archivo->getActiveSheet()->getStyle('A4:L4')->getFont()
         ->applyFromArray( [ 'name' => 'Arial', 'bold' => TRUE, 'italic' => FALSE,'strikethrough' => FALSE,'size'=>10, 'color' => [ 'rgb' => '000000' ] ] );
         $fila=5;
         foreach ($op as $key) 
         {
            $hoja->setCellValue('A'.$fila,$key->id);
            $hoja->setCellValue('B'.$fila,$key->ids);
            $hoja->setCellValue('C'.$fila,$key->name_sucursal);
            $hoja->setCellValue('D'.$fila,$key->coordenada);
            $hoja->setCellValue('E'.$fila,$key->fec_ope);
            $hoja->setCellValue('F'.$fila,$key->fecha);
            $hoja->setCellValue('G'.$fila,$key['usu/cli']);
            $hoja->setCellValue('H'.$fila,$key->peso);
            $hoja->setCellValue('I'.$fila,$key->tlf);
            $hoja->setCellValue('J'.$fila,$key->tipo);
            $hoja->setCellValue('K'.$fila,$key->ref);
            $hoja->setCellValue('L'.$fila,$key->status);
            
            $fila++;

         }

          //aqui para descargar excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Reporte.xlsx"');
        $writer=IOFactory::createWriter($archivo,'Xlsx');
        $writer->save("php://output");
        exit;
         
        }

        public function repodias(Request $request)
        {
            $year=$request->year;
            $mount=$request->mes;
            $cope=operation::whereYear('created_at',$year)->whereMonth('created_at',$mount)->where('usu/cli','cliente')
            ->where(function($query)
            {
                return $query->orwhere('status','Terminada')
                             ->orWhere('status','Cliente NR');
            });

           $extra=$cope->select('ids','name_sucursal',DB::raw('SUM(peso)'))->groupBy('ids','name_sucursal')->get();

           $terminada=$cope->select('ids',DB::raw('count(*) AS termi'))->where('status','Terminada')->groupBy('ids')->get();
           $clientenr=$cope->select('ids',DB::raw('count(*) AS nrcliente'))->where('status','Cliente NR')->groupBy('ids')->get();

            foreach ($extra as $key)
            {
               foreach ($terminada as $ter) 
               {
                  if($key->ids==$ter->ids)
                  {
                      $key->terminadas=$ter->termi;
                  }
               }

               foreach ($clientenr as $nr) 
               {
                   # code...
               }
            }
            // $cuadrito=$cuadrito->select('vehicleID',DB::raw('count ("vehicleID") as cu'),DB::raw('SUM(unload_weight)'),DB::raw('MAX(operations.time_in) AS ult'))->groupBy('operations.vehicleID')->get();
            return $extra;
        }
}