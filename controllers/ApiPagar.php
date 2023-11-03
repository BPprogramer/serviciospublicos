<?php 
    namespace Controllers;

use Model\Caja;
use Model\CajaPago;
use Model\Factura;
use Model\Estrato;
use Model\Pago;
use Model\Registrado;
use Model\Usuario;

    class ApiPagar{
        public static function facturasPorPagar(){
            $fechaMin = date('Y-m', strtotime('-3 month'));
            $fechaMax = date('Y-m');
           
           // $facturas = Factura::datosPorFecha('fecha_emision',$fecha);
            $facturas = Factura::rangoFecha('fecha_emision',$fechaMin , $fechaMax);
            
            
          
       
                foreach($facturas as $key=>$factura){
                    if(!$factura->registrado_id) continue;
                    $registrado = Registrado::find($factura->registrado_id);
                    $factura->registrado_nombre = $registrado->nombre;
              
                }
      
              echo json_encode($facturas);
        }


        public static function pagar(){
            if(!is_auth()){
                header('Location:/login');
            }

            $caja = Caja::get(1);
            if(!$caja){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Para realizar un pago debe abrir una caja']);
                return;
            }
            if($caja->estado == null){
                 echo json_encode(['tipo'=>'error', 'mensaje'=>'Para realizar un pago debe abrir una caja']);
                return;
            }
            if($caja->estado == 0){
                 echo json_encode(['tipo'=>'error', 'mensaje'=>'Para realizar un pago debe abrir una caja']);
                return;
            }


            $jsonData = $_POST['pagos'];
            $pagos = json_decode($jsonData, true);
            
            foreach($pagos as $pago){
                $id = $pago;
       
                if(!$id){
                    echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un Error, Porfavor intenta nuevamente']);
                    return;
                }
                $factura = Factura::find($id);
                if(!$factura){
                    echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un Error, Porfavor intenta nuevamente']);
                    return;
                }
                $factura->pagado = 1;
                $resultado = $factura->guardar();
                if(!$resultado){
                    echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un Error, Porfavor intenta nuevamente']);
                    return;
                }
                $registrado = Registrado::find($factura->registrado_id);
                if(!$registrado){
                    echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un Error, Porfavor intenta nuevamente']);
                    return;
                }
                $registrado->facturas = 0;
                
    
                $resultado = $registrado->guardar();
                if(!$resultado){
                    echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un Error, Porfavor intenta nuevamente']);
                    return;
                }
                $pago = Pago::get(1);
             
                $numero_pago = 0;
                if($pago){
                    $numero_pago = $pago->numero_pago + 1;
                }else{
                    $numero_pago = 200000;
                }
    
                $arrayPago = [
                    'numero_pago'=> $numero_pago,
                    'fecha_pago'=>date('Y-m-d'),
                    'metodo'=> 1,
                    'estado'=>1,
                    'factura_id'=>$factura->id, 
                    'registrado_id'=>$factura->registrado_id,
                    'usuario_id'=>$_SESSION['id']
    
                ];
    
                $pago = new Pago($arrayPago);
                
                $resultado = $pago->guardar();
                if(!$resultado){
                    echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un Error, Porfavor intenta nuevamente']);
                    return;
                }
                $arrayCajaPagos = [
                    'caja_id' => $caja->id,
                    'pago_id'=> $resultado['id']
                ];
    
                $cajaPagos = new CajaPago($arrayCajaPagos);
                $resultado = $cajaPagos->guardar();
    
            
                if(!$resultado){
                    echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un Error, Porfavor intenta nuevamente']);
                    return;
                }
    
               
                
            }
     
     
            echo json_encode(['tipo'=>'success', 'mensaje'=>'Lo pagos han sido subidos exitosamente']);
            return;

            

           
            
          
        }
      
        public static function pagos(){
            $pagos = Pago::all();
            $i=0;
            $datoJson = '{
             "data": [';
                 foreach($pagos as $key=>$pago){
                     $i++;

                    

                     $subscriptor = 'Cliente Eliminado';
                     if($pago->registrado_id){
                        
                        $subscriptor = Registrado::find($pago->registrado_id);
                        $subscriptor = $subscriptor->nombre. ' '.$subscriptor->apellido;
                       
                     }
                     $usuario = 'Usuario Eliminado';
                     if($pago->usuario_id){
                        
                        $usuario = Usuario::find($pago->usuario_id);
                        $usuario = $usuario->nombre. ' '.$usuario->apellido;
                       
                     }

                     $factura = Factura::find($pago->factura_id);
                     $monto = $factura->copago-$factura->ajuste+$factura->saldo_anterior;
     
                 
                    
                     $acciones = "<div class='table__td--acciones'>";

                     $acciones .= "<button  class='table__accion table__accion--editar' id='btn_previsualizar' data-numero-pago='$pago->factura_id'><i class='fa-solid fa-print'></i></button>";
                     $acciones .= "<button  class='table__accion table__accion--eliminar' id='btn_anular' data-numero-pago='$pago->numero_pago'><i class='fa-solid fa-trash'></i></button>";

                     
                     $acciones .= "</div>";
                    
                   
                     $estado = "<span class='table__td--activo'>Verificado</span>";
                     if($pago->estado != 1){
                        $estado = "<span class='table__td--inactivo' >Anulado</span>";
                     }
                     
                     $datoJson.= '[
                             "'.$i.'",
                             "'.$pago->numero_pago.'",
                             "'.$factura->numero_factura.'",
                             "'.$subscriptor.'",
                          
                          
                             "'.$pago->fecha_pago.'",
                             "'.number_format($monto).'",
                             "'.$usuario.'",
                             "'.$estado.'",
                          
        
                     
                             "'.$acciones.'"
                     ]';
                     if($key != count($pagos)-1){
                         $datoJson.=",";
                     }
                 }
       
             $datoJson.=  ']}';
            echo $datoJson;


     
        }
    }