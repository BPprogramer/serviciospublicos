<?php 
    namespace Controllers;

use Model\Caja;
use Model\CajaPago;
use Model\Estrato;
use Model\Factura;
use Model\Pago;
use Model\Registrado;
use Model\Usuario;

    class ApiCajasPagos{

        public static function informacionCaja(){
            $id =$_GET['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if(!$id){
                header('Location:/admin/cajas');
            }

            $cajas_pagos = CajaPago::whereArray(['caja_id'=>$_GET['id']]);
            
  
           
            
          
            $i=0;
            $datoJson = '{
            "data": [';
                foreach($cajas_pagos as $key=>$caja){
                    $i++;

                    $pago = Pago::find($caja->pago_id);
             
 
                    
  
                    $factura = Factura::find($pago->factura_id);
                    $usuario = Usuario::find($pago->usuario_id);
                    $registrado = Registrado::find($factura->registrado_id);
                   
                    
                    $responsable = '';
                    if($usuario){
                        $responsable= explode(' ',trim($usuario->nombre))[0] .' '. explode(' ',trim($usuario->apellido))[0];
                    }
                    $metodo = 'Efectivo';
                    if($pago->metodo ==0){
                        $metodo = 'Transferencia';
                    }
 
                    if($pago->estado==0){
                        $datoJson.= '[
                            "anulado",
                            "anulado",
                            "anulado",
                         
                            
                            "anulado",
                            "anulado",
                            "anulado",
                    
                            "anulado"
                    ]';
                    if($key != count($cajas_pagos)-1){
                        $datoJson.=",";
                    }
                    continue;
                    }
              
                
       
                    $datoJson.= '[
                            "'.$i.'",
                            "'.$responsable.'",
                            "Pago",
                         
                            
                            "$'.number_format($factura->copago-$factura->ajuste+$factura->saldo_anterior).'",
                            "'.$metodo.'",
                            "'.$registrado->nombre.'",
                    
                            "'.$pago->fecha_pago.'"
                    ]';
                    if($key != count($cajas_pagos)-1){
                        $datoJson.=",";
                    }
                }
      
            $datoJson.=  ']}';
           echo $datoJson;
        }
        public static function eliminar(){
          
            $id = $_GET['id'];
            
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if(!$id){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un error, porfavor Intenta Nuevamente']);
                return;

            }
            $estrato = Estrato::find($id);
            if(!isset($estrato)){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Estrato no encontrado, porfavor intente nuevamente']);
                return;
            }
            $resultado = $estrato->eliminar();
            if(!$resultado){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un error, porfavor Intenta Nuevamente']);
                return;
            }
            echo json_encode(['tipo'=>'success', 'mensaje'=>'Estrato eliminado correctamente']);
            
         }
     
        public static function informacion(){
            $id = $_GET['id'];
    
            
            $id = filter_var($id, FILTER_VALIDATE_INT);
          
            if(!$id){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un error, porfavor Intenta Nuevamente']);
                return;

            }
     

            $estrato = Estrato::find($id);
    
            if(!isset($estrato)){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Estrato no encontrado, porfavor intente nuevamente']);
                return;
            }
           
            echo json_encode($estrato);
        }
    }