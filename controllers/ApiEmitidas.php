<?php 

    namespace Controllers;

use Model\Factura;
use Model\Registrado;

    class ApiEmitidas{
        public static function emitidasPendientes(){
            $emitidas = Factura::fechas(date('Y-m', strtotime('-1 month')));

            $emitidasPendientes = array_filter($emitidas, function($emitida){
                if($emitida->pagado==0 && $emitida->combinado==0){
                    return $emitida;
                }
          
            });
         
     
            $i=0;
            $datoJson = '{
             "data": [';
                 foreach($emitidasPendientes as $key=>$emitidaPendiente){
                     $i++;

                    
                    $registrado = Registrado::find($emitidaPendiente->registrado_id);
        
                    if(!$registrado){
                        $nombre_completo = 'No identificado';
                     
                    }else{
                        $nombre_completo = $registrado->nombre.' '.$registrado->apellido;
                    }
                    $monto = $emitidaPendiente->copago+$emitidaPendiente->saldo_anterior-$emitidaPendiente->ajuste;
              
                     $acciones = "<div class='table__td--acciones'>";
                     $acciones .= "<button  class='table__accion table__accion--editar' id='btn_previsualizar' data-numero-factura='$emitidaPendiente->numero_factura'><i class='fa-solid fa-print'></i></button>";
                     $acciones .= "</div>";
                     
                     $datoJson.= '[
                             "'.$i.'",
                             "#'.$emitidaPendiente->numero_factura.'",
                             "'.$nombre_completo.'",
                             "'.$emitidaPendiente->mes_facturado.'",
                          
                          
                             "'.$emitidaPendiente->estrato.'",
                             "$'.number_format($monto).'",
                       
                          
        
                     
                             "'.$acciones.'"
                     ]';
                     if($i-1 != count($emitidasPendientes)-1){
                         $datoJson.=",";
                     }
                 }
       
             $datoJson.=  ']}';
            echo $datoJson;
        }
    }