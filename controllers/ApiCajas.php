<?php 
    namespace Controllers;

use Model\Caja;
use Model\CajaPago;
use Model\Estrato;
use Model\Factura;
use Model\Pago;
use Model\Usuario;

    class ApiCajas{
        public static function index(){
            $cajas = Caja::all();
            
          
            $i=0;
            $datoJson = '{
            "data": [';
                foreach($cajas as $key=>$caja){
                    $i++;

                    $usuario = Usuario::find($caja->usuario_id);
                    $responsable = '';
                    if($usuario){
                        $responsable=$usuario->nombre. ' '.$usuario->apellido ;
                    }
 
                 
                    $acciones = "<div class='table__td--acciones'>";
                    $acciones .= "<a class='table__accion table__accion--editar' href='/admin/cajas/pagos?id=$caja->id'><i class='fa-solid fa-search'></i></a> ";
                 

                    $acciones .= "</div>";

                    $efectivo_inicial = number_format($caja->efectivo_inicial);
                    $total_recaudo = number_format($caja->total_recaudo);
                    $total_efectivo = number_format($caja->total_efectivo);
                    $total_transferencias = number_format($caja->total_transferencias);
                    $estado = "<span id='estadoCaja' data-caja-id = '$caja->id'  class='table__td--activo'>Abierta</span>";
                    if($caja->estado == 0){
                        $estado = "<span  class='table__td--neutro'>Cerrada</span>";
                    }
                
       
                    $datoJson.= '[
                            "'.$i.'",
                            "'.$responsable.'",
                            "'.$efectivo_inicial.'",
                         
                            
                            "$'.$total_recaudo.'",
                            "$'.$total_efectivo.'",
                            "$'.$total_transferencias.'",
                    
                            "'.$estado.'",
                            "'.$acciones.'"
                    ]';
                    if($key != count($cajas)-1){
                        $datoJson.=",";
                    }
                }
      
            $datoJson.=  ']}';
           echo $datoJson;
        }
        public static function cerrar(){
          
            if($_SERVER['REQUEST_METHOD']=='POST'){
                $id =$_POST['id'];
                $id = filter_var($id, FILTER_VALIDATE_INT);

                if(!$id){
                    echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un error, porfavor Intenta Nuevamente']);
                    return;
    
                }

                $caja = Caja::find($id);
                if(!isset($caja)){
                    echo json_encode(['tipo'=>'error', 'mensaje'=>'Caja no encontrada, porfavor intente nuevamente']);
                    return;
                }
                $cajaPagos = CajaPago::whereArray(['caja_id'=>$caja->id]);
                $recaudo = 0;
                $efectivo_caja = 0;
                $transferencias = 0;
                foreach($cajaPagos as $cajaPago){
                    $pago = Pago::find($cajaPago->pago_id);
                   
                    $factura = Factura::find($pago->factura_id);
                    if($pago->metodo==1){
                        $efectivo_caja = $efectivo_caja + $factura->copago;
                    }else{
                        $transferencias = $transferencias + $factura->copago;
                    }
            
                 
                    $recaudo = $recaudo + $factura->copago;
    
                 
                }
    
                
    
                $caja->total_recaudo = $recaudo  + $caja->efectivo_inicial ;
                $caja->total_efectivo =  $efectivo_caja + $caja->efectivo_inicial ;
                $caja->total_transferencias = $transferencias;
                
                $caja->estado = 0;
                date_default_timezone_set('America/Bogota');
                $caja->fecha_cierre = date("Y-m-d H:i:s");
                

                $resultado = $caja->guardar();
        
                

    
                if(!$resultado){
                    echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un error, porfavor intente nuevamente']);
                    return;
                }
           
                echo json_encode(['tipo'=>'success', 'mensaje'=>'Caja Cerrada exitosamente correctamente']);

                return;
                
            }
           
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