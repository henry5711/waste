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
        })
        ->when($request->sta,function($query,$sta){
            //buscar por estatus
            return $query->where('status','ILIKE',"%$sta%");
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
        })
        ->when($request->sta,function($query,$sta){
            //buscar por estatus
            return $query->where('status','ILIKE',"%$sta%");
        })->orderBy('id','desc')->get();

     
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
       $archivo->getActiveSheet()->getColumnDimension('K')->setWidth(260, 'px');
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
         $hoja->setCellValue('D4','FECHA DE OPERACION');
         $hoja->setCellValue('E4','FECHA DE REGISTRO');
         $hoja->setCellValue('F4','USUARIO/CLIENTE');
         $hoja->setCellValue('G4','PESO');
         $hoja->setCellValue('H4','TELEFONO');
         $hoja->setCellValue('I4','WEB/APP');
         $hoja->setCellValue('J4','REFERENCIA');
         $hoja->setCellValue('K4','OBSERVACION');
         $hoja->setCellValue('L4','ESTADO');
        
         //TAMAÑO DEL TITULO
         $archivo->getActiveSheet()->getStyle('A4:L4')->getFont()
         ->applyFromArray( [ 'name' => 'Arial', 'bold' => TRUE, 'italic' => FALSE,'strikethrough' => FALSE,'size'=>10, 'color' => [ 'rgb' => 'ffffff' ] ] );
         $fila=5;
         foreach ($op as $key) 
         {
            $hoja->setCellValue('A'.$fila,$key->id);
            $hoja->setCellValue('B'.$fila,$key->ids);
            $hoja->setCellValue('C'.$fila,$key->name_sucursal);
            $hoja->setCellValue('D'.$fila,$key->fecha);
            $hoja->setCellValue('E'.$fila,$key->fecha_ope);
            $hoja->setCellValue('F'.$fila,$key['usu/cli']);
            $hoja->setCellValue('G'.$fila,$key->peso);
            $hoja->setCellValue('H'.$fila,$key->tlf);
            $hoja->setCellValue('I'.$fila,$key->tipo);
            $hoja->setCellValue('J'.$fila,$key->ref);
            $hoja->setCellValue('K'.$fila,$key->obs);
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
        
            $cope=operation::where('usu/cli','cliente')
            ->where(function($query)
            {
                return $query->orwhere('status','Terminada')
                             ->orWhere('status','Cliente NR');
            })
            ->when($request->date, function($query, $interval){
                $date = explode('_', $interval);
                $date[0] = Carbon::parse($date[0])->format('Y-m-d');
                $date[1] = Carbon::parse($date[1])->format('Y-m-d');
                return $query->whereBetween(
                    DB::raw("TO_CHAR(fecha,'YYYY-MM-DD')"),[$date[0],$date[1]]);
            });

            $date = explode('_', $request->date);
            $dateone=Carbon::createFromFormat('Y-m-d', $date[0]);   
            $datetow=Carbon::createFromFormat('Y-m-d', $date[1]);   
            $diff=$dateone->diffInDays($datetow);
            
            $c=$cope;
        
            $pintar=$cope->select('ids','name_sucursal',DB::raw('SUM(peso)'))->groupBy('ids','name_sucursal')->get();
        
            $extra=$c->select('ids','name_sucursal','fecha',DB::raw('SUM(peso)'))->groupBy('ids','name_sucursal','fecha')->get();
           

           foreach($pintar as $key)
           {
               foreach($extra as $value)
               {
                   if ($key->ids==$value->ids)
                   {
                      $key->trabajados+=1;
                   }
               }
           }

           foreach($pintar as $key)
           {
              $key->notrabajados=$diff-$key->trabajados;

              if($key->trabajados>0 and $key->notrabajados>0)
              {
                  $key->promedio=$key->trabajados/$key->notrabajados;
              }

              else
              {
                $key->promedio=0;
              }
           }

          
           

            $ar=['F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ'];
             //aqui se crea el excel
       $archivo=new Spreadsheet();
       //aqui la hoja
      $hoja=$archivo->getActiveSheet();
      $hoja->setTitle("Operaciones");

      $hoja->mergeCells('A1:E1');
      $hoja->setCellValue('A1','REPORTE MENSUAL DE SUCURSALES VISITADAS');

      //ancho de las celdas
      $archivo->getActiveSheet()->getColumnDimension('A')->setWidth(270, 'px');
      $archivo->getActiveSheet()->getColumnDimension('B')->setWidth(220, 'px');
      $archivo->getActiveSheet()->getColumnDimension('C')->setWidth(220, 'px');
      $archivo->getActiveSheet()->getColumnDimension('D')->setWidth(270, 'px');
      $archivo->getActiveSheet()->getColumnDimension('E')->setWidth(220, 'px');

        //AQUI CENTRO LOS TITULOS
        $archivo->getActiveSheet()->getStyle('A:E')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        //COLOR  al primer cuadro
        $archivo->getActiveSheet()->getStyle('A3:E3')->getFill()
           ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
           ->getStartColor()->setRGB('416DA9');

        //titulos
        $hoja->setCellValue('A3','SUCURSALES');
        $hoja->setCellValue('B3','Total de lbs reciclado');
        $hoja->setCellValue('C3','# de recolectas / local');
        $hoja->setCellValue('D3','# de recolectas no relizadas / local');
        $hoja->setCellValue('E3','Promedio');
       
        //TAMAÑO DEL TITULO
        $archivo->getActiveSheet()->getStyle('A3:E3')->getFont()
        ->applyFromArray( [ 'name' => 'Arial', 'bold' => TRUE, 'italic' => FALSE,'strikethrough' => FALSE,'size'=>12, 'color' => [ 'rgb' => 'ffffff' ] ] );

        $fila=4;
        $total=0;
            foreach ($pintar as $value)
            {
                $hoja->setCellValue('A'.$fila,$value->name_sucursal);
                $hoja->setCellValue('B'.$fila,$value->sum);
                $hoja->setCellValue('C'.$fila,$value->trabajados);
                $hoja->setCellValue('D'.$fila,$value->notrabajados);
                if($value->trabajados !=null and $value->notrabajados !=null or $value->trabajados !=0 and $value->notrabajados !=0  )
                {
                    $hoja->setCellValue('E'.$fila,$value->trabajados/$value->notrabajados);
                }

                else
                {
                    $hoja->setCellValue('E'.$fila,0);
                }
               
                $total+=$value->sum;


                /*idea que no sirvio
                $ox=operation::where('ids',$value->ids)->whereYear('created_at',$year)->whereMonth('created_at',$mount)->orderBy('fecha_ope')->get();
                for ($i=0; $i = 30 ; $i++)
                { 
                  //aqui tarda optimizar
                  foreach ($ox as $rope) 
                  {
                    $hoja->setCellValue($ar[$i].$fila,$rope->peso);
                  }
                } */

                $fila++;
            }
            
            $t=count($pintar);
            if($total >0 and $t >0)
            {
                $div=$total/$t;
            }

            else 
            {
                $div=0;
            }
           
            $hoja->setCellValue('B'.$fila,'TOTAL: '.$total);
            $hoja->setCellValue('A'.$fila,"AVERAGE: $div");
            

             //aqui para descargar excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Reporte mensual sucursal.xlsx"');
        $writer=IOFactory::createWriter($archivo,'Xlsx');
        $writer->save("php://output");
        exit;
        }

        public function sucurconsulta(Request $request)
        {
            $cope=operation::where('usu/cli','cliente')
            ->where(function($query)
            {
                return $query->orwhere('status','Terminada')
                             ->orWhere('status','Cliente NR');
            })
            ->when($request->date, function($query, $interval){
                $date = explode('_', $interval);
                $date[0] = Carbon::parse($date[0])->format('Y-m-d');
                $date[1] = Carbon::parse($date[1])->format('Y-m-d');
                return $query->whereBetween(
                    DB::raw("TO_CHAR(fecha,'YYYY-MM-DD')"),[$date[0],$date[1]]);
            });

            $date = explode('_', $request->date);
            $dateone=Carbon::createFromFormat('Y-m-d', $date[0]);   
            $datetow=Carbon::createFromFormat('Y-m-d', $date[1]);   
            $diff=$dateone->diffInDays($datetow);
            
            $c=$cope;
        
            $pintar=$cope->select('ids','name_sucursal',DB::raw('SUM(peso)'))->groupBy('ids','name_sucursal')->get();
        
            $extra=$c->select('ids','name_sucursal','fecha',DB::raw('SUM(peso)'))->groupBy('ids','name_sucursal','fecha')->get();
           

           foreach($pintar as $key)
           {
               foreach($extra as $value)
               {
                   if ($key->ids==$value->ids)
                   {
                      $key->trabajados+=1;
                   }
               }
           }

           foreach($pintar as $key)
           {
              $key->notrabajados=$diff-$key->trabajados;

              if($key->trabajados>0 and $key->notrabajados>0)
              {
                  $key->promedio=$key->trabajados/$key->notrabajados;
              }

              else
              {
                $key->promedio=0;
              }
           }

            return ["list"=>$pintar,"total"=>count($pintar)];
        }

        public function diawork(Request $request)
        {

            //esta funcion devuelve la consulta de los dias trabajados pero desde que encuentra la primera operacion de cada sucursal
            $cope=operation::where('usu/cli','cliente')
            ->where(function($query)
            {
                return $query->orwhere('status','Terminada')
                             ->orWhere('status','Cliente NR');
            })
            ->when($request->date, function($query, $interval){
                $date = explode('_', $interval);
                $date[0] = Carbon::parse($date[0])->format('Y-m-d');
                $date[1] = Carbon::parse($date[1])->format('Y-m-d');
                return $query->whereBetween(
                    DB::raw("TO_CHAR(fecha,'YYYY-MM-DD')"),[$date[0],$date[1]]);
            });

            
            $date = explode('_', $request->date);
            $dateone=Carbon::createFromFormat('Y-m-d', $date[0]);   
            $datetow=Carbon::createFromFormat('Y-m-d', $date[1]);   
          
            $c=$cope;
        
            $pintar=$cope->select('ids','name_sucursal',DB::raw('SUM(peso)'))->groupBy('ids','name_sucursal')->get();
        
            $extra=$c->select('ids','name_sucursal','fecha',DB::raw('SUM(peso)'))->groupBy('ids','name_sucursal','fecha')->get();
           

           foreach($pintar as $key)
           {
               foreach($extra as $value)
               {
                   if ($key->ids==$value->ids)
                   {
                      $key->trabajados+=1;
                   }
               }
           }

           foreach($pintar as $key)
           {
               $fecno=operation::where('usu/cli','cliente')
               ->where('ids',$key->ids)
               ->where(function($query)
               {
                   return $query->orwhere('status','Terminada')
                                ->orWhere('status','Cliente NR');
               })
               ->when($request->date, function($query, $interval){
                   $date = explode('_', $interval);
                   $date[0] = Carbon::parse($date[0])->format('Y-m-d');
                   $date[1] = Carbon::parse($date[1])->format('Y-m-d');
                   return $query->whereBetween(
                       DB::raw("TO_CHAR(fecha,'YYYY-MM-DD')"),[$date[0],$date[1]]);
               })->first();
               //dd($fecno);
              $dateini=Carbon::createFromFormat('Y-m-d', $fecno->fecha);  
              $key->inicial=$fecno->fecha;
              $diffe=$dateini->diffInDays($datetow);
              if($diffe >0)
              {
                $key->notrabajados=$diffe-$key->trabajados;
              }

              else
            {
                $key->notrabajados=0;
            }
            
             
             

              if($key->trabajados>0 and $key->notrabajados>0)
              {
                  $key->promedio=$key->trabajados/$key->notrabajados;
              }

              else
              {
                $key->promedio=0;
              }
           }

           return ["list"=>$pintar,"total"=>count($pintar)];
        }

        public function exceldiaswork(Request $request)
        {
             //esta funcion devuelve la consulta de los dias trabajados pero desde que encuentra la primera operacion de cada sucursal
             $cope=operation::where('usu/cli','cliente')
             ->where(function($query)
             {
                 return $query->orwhere('status','Terminada')
                              ->orWhere('status','Cliente NR');
             })
             ->when($request->date, function($query, $interval){
                 $date = explode('_', $interval);
                 $date[0] = Carbon::parse($date[0])->format('Y-m-d');
                 $date[1] = Carbon::parse($date[1])->format('Y-m-d');
                 return $query->whereBetween(
                     DB::raw("TO_CHAR(fecha,'YYYY-MM-DD')"),[$date[0],$date[1]]);
             });
 
             $date = explode('_', $request->date);
             $dateone=Carbon::createFromFormat('Y-m-d', $date[0]);   
             $datetow=Carbon::createFromFormat('Y-m-d', $date[1]);   
             $diff=$dateone->diffInDays($datetow);
             
             $c=$cope;
         
             $pintar=$cope->select('ids','name_sucursal',DB::raw('SUM(peso)'))->groupBy('ids','name_sucursal')->get();
         
             $extra=$c->select('ids','name_sucursal','fecha','peso')->groupBy('ids','name_sucursal','fecha','peso')->get();
            
            return $pintar;
 
            foreach($pintar as $key)
            {
                foreach($extra as $value)
                {
                    if ($key->ids==$value->ids)
                    {
                       $key->trabajados+=1;
                    }
                }
            }
 
            foreach($pintar as $key)
            {
                $fecno=operation::where('usu/cli','cliente')
                ->where('ids',$key->ids)
                ->where(function($query)
                {
                    return $query->orwhere('status','Terminada')
                                 ->orWhere('status','Cliente NR');
                })
                ->when($request->date, function($query, $interval){
                    $date = explode('_', $interval);
                    $date[0] = Carbon::parse($date[0])->format('Y-m-d');
                    $date[1] = Carbon::parse($date[1])->format('Y-m-d');
                    return $query->whereBetween(
                        DB::raw("TO_CHAR(fecha,'YYYY-MM-DD')"),[$date[0],$date[1]]);
                })->first();
             
               $dateini=Carbon::createFromFormat('Y-m-d', $fecno->fecha);  
               $key->inicial=$fecno->fecha;
               $diffe=$dateini->diffInDays($datetow);
               if($diffe >0)
               {
                 $key->notrabajados=$diffe-$key->trabajados;
               }
 
               else
             {
                 $key->notrabajados=0;
             }
              
            
 
               if($key->trabajados>0 and $key->notrabajados>0)
               {
                   $key->promedio=$key->trabajados/$key->notrabajados;
               }
 
               else
               {
                 $key->promedio=0;
               }
            }
            
              //aqui se crea el excel
       $archivo=new Spreadsheet();
       //aqui la hoja
      $hoja=$archivo->getActiveSheet();
      $hoja->setTitle("Operaciones");

      $hoja->mergeCells('A1:E1');
      $hoja->setCellValue('A1','REPORTE MENSUAL DE SUCURSALES VISITADAS');

      //ancho de las celdas
      $archivo->getActiveSheet()->getColumnDimension('A')->setWidth(270, 'px');
      $archivo->getActiveSheet()->getColumnDimension('B')->setWidth(220, 'px');
      $archivo->getActiveSheet()->getColumnDimension('C')->setWidth(220, 'px');
      $archivo->getActiveSheet()->getColumnDimension('D')->setWidth(270, 'px');
      $archivo->getActiveSheet()->getColumnDimension('E')->setWidth(270, 'px');
      $archivo->getActiveSheet()->getColumnDimension('F')->setWidth(220, 'px');


        //AQUI CENTRO LOS TITULOS
        $archivo->getActiveSheet()->getStyle('A:F')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        //COLOR  al primer cuadro
        $archivo->getActiveSheet()->getStyle('A3:F3')->getFill()
           ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
           ->getStartColor()->setRGB('416DA9');

        //titulos
        $hoja->setCellValue('A3','SUCURSALES');
        $hoja->setCellValue('B3','Total de lbs reciclado');
        $hoja->setCellValue('C3','Fecha inicial');
        $hoja->setCellValue('D3','# de recolectas / local');
        $hoja->setCellValue('E3','# de recolectas no relizadas / local');
        $hoja->setCellValue('F3','Promedio');
       
        //TAMAÑO DEL TITULO
        $archivo->getActiveSheet()->getStyle('A3:F3')->getFont()
        ->applyFromArray( [ 'name' => 'Arial', 'bold' => TRUE, 'italic' => FALSE,'strikethrough' => FALSE,'size'=>12, 'color' => [ 'rgb' => 'ffffff' ] ] );
           
        $fila=4;
        $columns=range('G','Z');
      
        $total=0;
        foreach ($pintar as $value)
        {
            $hoja->setCellValue('A'.$fila,$value->name_sucursal);
            $hoja->setCellValue('B'.$fila,$value->sum);
            $hoja->setCellValue('C'.$fila,$value->inicial);
            $hoja->setCellValue('D'.$fila,$value->trabajados);
            $hoja->setCellValue('E'.$fila,$value->notrabajados);
            if($value->trabajados !=null and $value->notrabajados !=null or $value->trabajados !=0 and $value->notrabajados !=0  )
            {
                $hoja->setCellValue('F'.$fila,$value->trabajados/$value->notrabajados);
            }

            else
            {
                $hoja->setCellValue('F'.$fila,0);
            }
            $i=0;
                foreach($extra as $act)
                {
                    if ($act->ids==$value->ids)
                    {
                           
                            $hoja->setCellValue($columns[$i].$fila,$act->peso);
                            $i++; 
                    }
                 
                }
            

           
            $total+=$value->sum;

            $fila++;

        }

        
        
        $t=count($pintar);
           if($total >0 and $t >0)
            {
                $div=$total/$t;
            }

            else 
            {
                $div=0;
            }
       
        $hoja->setCellValue('B'.$fila,'TOTAL: '.$total);
        $hoja->setCellValue('A'.$fila,"AVERAGE: $div");
        

         //aqui para descargar excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Reporte mensual sucursal.xlsx"');
    $writer=IOFactory::createWriter($archivo,'Xlsx');
    $writer->save("php://output");
    exit;
           
        }
}