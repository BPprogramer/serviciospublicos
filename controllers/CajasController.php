<?php 

    namespace Controllers;

use Model\Caja;
use Model\CajaPago;
use MVC\Router;
    use Model\Estrato;
use Model\Factura;
use Model\Pago;
use Model\Usuario;

    class CajasController{
        public static function index(Router $router){
            
            $router->render('admin/cajas/index',[
                'titulo'=>'Cajas'
            ]);
        }

        public static function cajasPagos(Router $router){
            $id = $_GET['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if(!$id){
                header(('Location:/admin/cajas'));
            }
            $caja = Caja::find($id);
            $usuario = Usuario::find($caja->usuario_id);
      
            $caja->responsable = $usuario->nombre. ' '.$usuario->apellido ;
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



            $caja->total_recaudo = $recaudo;
            $caja->total_efectivo =  $efectivo_caja ;
            $caja->total_transferencias = $transferencias;
          

            $router->render('admin/cajas/cajas-pagos',[
                'titulo'=>'Información sobre la Caja',
                'caja'=>$caja
            ]);
        }

     
        public static function abrir(Router $router){
            
            $alertas = [];
            $caja = new Caja();

            if($_SERVER['REQUEST_METHOD']=='POST'){
                
             
                $caja->sincronizar($_POST);
                $ultimaCaja = Caja::get(1);
                $estado_actual = 1;
                if($ultimaCaja == null){
                    $estado_actual = 0;
                }else{
                    $estado_actual = $ultimaCaja->estado;
                }
              
        
                if($estado_actual==1){
                    Caja::setAlerta('error', 'Ya existe una caja Abierta');
                }else{
                    $alertas = $caja->validar();
                    if(empty($alertas)){
                        
                        $caja->formatearDatosFloat();
                        $caja->estado = 1;
                        session_start();
                        $caja->usuario_id = $_SESSION['id'];

                        $caja->fecha_apertura =  date("Y-m-d H:i:s");
                        $caja->fecha_cierre = "2000-01-01 01:01:01";
                
            
                      
                   
                    
                        
                        $resultado =  $caja->guardar();
                        if($resultado){
                            $caja = new Caja([]);
                            Caja::setAlerta('exito', 'Caja abierta exitosamente, Redireccionando');
                       
                        }
                    }
                }

          

   
                
                
            }
            $alertas = Caja::getAlertas();
          
            $router->render('admin/cajas/abrir',[
                'titulo'=>'Abrir Caja',
                'alertas'=>$alertas,
                'caja'=>$caja,
                'year'=> date('Y')-1
            ]);
        }
        public static function editar(Router $router){
            
            $alertas = [];
            $id = $_GET['id'];
      
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if(!$id){
                header('Location:/admin/estratos');
            }
            $estratos = Estrato::find($id);
            if(!$estratos){
                header('location:/admin/estratos');
            }
            $estratos->formatearDatosNumber();
            if($_SERVER['REQUEST_METHOD']=='POST'){
                
                $estratos->sincronizar($_POST);
           
                $estratos->validarAjuste();
                $alertas = $estratos->validar();
                if(empty($alertas)){
            
                    $estratos->formatearDatosFloat();
                    $resultado =  $estratos->guardar();
                    if($resultado){
                        $estratos = new Estrato([]);
                        Estrato::setAlerta('exito', 'Estrato Actualizado exitosamente, Redireccionando');
                        $alertas = Estrato::getAlertas();
                    }
                }
                
            }
            
            $router->render('admin/estratos/editar',[
                'titulo'=>'Editar Estrato',
                'alertas'=>$alertas,
                'estratos'=>$estratos,
                'year'=> date('Y')-1
            ]);
        }
    }