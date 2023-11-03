<?php 
    namespace Controllers;

use Model\Factura;
use Model\Pago;
use Model\Registrado;

    class ApiInicio{
        public static function registrados(){
            if(!is_auth()){
                header('Location:/login');
            }

            $registrados = Registrado::total();
            $registrados_activos = Registrado::total('estado', 1);
            $pagos_vigentes = Pago::total('estado',1);

            $pagos = Pago::all();
            $ingresos = 0;
         
            foreach($pagos as $pago){
                if($pago->estado==0) continue;
                $factura= Factura::find($pago->factura_id);
                
                $ingresos =$ingresos+ $factura->copago+$factura->saldo_anterior-$factura->ajuste;
              
            }

         
            $registrados_inactivos = $registrados-$registrados_activos;
            echo json_encode(['registrados'=>$registrados, 'registrados_activos'=>$registrados_activos,'registrados_inactivos'=>$registrados_inactivos, 'pagos_vigentes'=>$pagos_vigentes, 'ingresos'=>number_format($ingresos)]);

        }

        public static function fecha(){
            if($_SERVER['REQUEST_METHOD']=='POST'){
       
                $pagos = Pago::registrosPosteriores($_POST['fecha']);
                $pago_total = 0;
                foreach($pagos as $pago){
                    if($pago->estado==0) continue;
                    $factura= Factura::find($pago->factura_id);
                    $pago_total =$pago_total+ $factura->copago+$factura->saldo_anterior-$factura->ajuste;
                }
                

                echo json_encode(number_format($pago_total));
                

                
                return;
            }
          
        }
        public static function ingresosMensuales(){
            if($_SERVER['REQUEST_METHOD']=='POST'){
       
                $pagos = Pago::datosPorFecha('fecha_pago',$_POST['fecha']);
                $ingresos = 0;
                foreach($pagos as $pago){
                    if($pago->estado==0) continue;
                    $factura= Factura::find($pago->factura_id);
                    $ingresos =$ingresos+ $factura->copago+$factura->saldo_anterior-$factura->ajuste;
                }
                

                echo json_encode(number_format($ingresos));
                

                
                return;
            }
        }
        public static function consignaciones(){
    
            if($_SERVER['REQUEST_METHOD']=='POST'){
       
                $facturas = Factura::datosPorFecha('mes_facturado',$_POST['fecha']);
                if(!$facturas){
                    echo json_encode(['error'=>'No Hay facturacion para el mes seleccionado']);
                    return;
                }

                $aseo= 0;
                $alcantarillado = 0;
                $acueducto = 0;
                $factor = 1;
                foreach($facturas as $factura){
                    if($factura->pagado==1){
                        
                     
                        $factor = round($factura->copago - $factura->ajuste + $factura->saldo_anterior)/($factura->copago - $factura->ajuste);
                       
                    
                      
                        $aseo = $aseo + $factor*$factura->copago_aseo;
                        $alcantarillado = $alcantarillado +$factor*$factura->copago_alc;
                        $acueducto = $acueducto + $factor*$factura->copago_acu;
                    }
                   
                }
                

                echo json_encode(['aseo'=>number_format($aseo), 'alcantarillado'=>number_format($alcantarillado), 'acueducto'=>number_format($acueducto)]);
                

                
                return;
            }
        }
    }