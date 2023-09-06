<?php 
    namespace Controllers;

use Model\Estrato;
use Model\Factura;
use Model\Registrado;
use Model\Usuario;
use MVC\Router;

    class RegistradosController{
        public static function index(Router $router){



            $router->render('admin/registrados/index',[
                'titulo'=> 'Registrados'
            ]);
        }
        public static function crear(Router $router){
            $alertas = [];
            $registrado = new Registrado();
            $estratos = Estrato::all();
          
         
        
            if($_SERVER['REQUEST_METHOD']=='POST'){
                $registrado->sincronizar($_POST);
                $registrado->facturas = 0;
                $alertas = $registrado->validar();
                if(empty($alertas)){
                    $resultado = $registrado->guardar();
                    if($resultado){
                        $registrado = new Registrado([]);
                        Registrado::setAlerta('exito', 'Subscriptor Creado Exitosamente, Redireccionando');
                        $alertas = Registrado::getAlertas();
                    }
                }
               
            }
            

            $router->render('admin/registrados/crear',[
                'titulo'=> 'Crear Subscriptor',
                'registrado'=> $registrado,
                'estratos'=>$estratos,
                'alertas'=>$alertas
            ]);
        }
        public static function editar(Router $router){
            $alertas = [];
            $id = $_GET['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if(!$id){
                header('Location:/admin/registrados');
            }
            $registrado = Registrado::find($id);
            if(!$registrado){
                header('Location:/admin/registrados');
            }
            $estratos = Estrato::all();
      
       
        
            if($_SERVER['REQUEST_METHOD']=='POST'){
                $registrado->sincronizar($_POST);
                $alertas = $registrado->validar();
                if(empty($alertas)){
                    $resultado = $registrado->guardar();
                    if($resultado){
                        $registrado = new Registrado([]);
                        Registrado::setAlerta('exito', 'Subscriptor Actualizado Exitosamente, Redireccionando');
                        $alertas = Registrado::getAlertas();
                    }
                }
               
            }
            

            $router->render('admin/registrados/editar',[
                'titulo'=> 'Editar Subscriptor',
                'registrado'=> $registrado,
                'estratos'=>$estratos,
                'alertas'=>$alertas
            ]);
        }

        public static function registradoInfo(Router $router){
            if(!is_auth()){
                header('Location:/login');
            }
            $id = $_GET['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if(!$id){
                header('Location:/admin/registrados');
            }
            $registrado = Registrado::find($id);
            if(!$registrado){
                header('Location:/admin/registrados');
            }
            $estrato = new Estrato();
            $registrado->estrato = $estrato;
            
            if($registrado->estrato_id){
                $estrato = Estrato::find($registrado->estrato_id);
                $estrato->formatearDatosNumber();
       
             
                $registrado->estrato = $estrato;
            }

            $facturas = Factura::whereArray(['registrado_id'=>$registrado->id]);

            foreach($facturas as $factura){
                $factura->fecha_emision = date("d-m-Y", strtotime($factura->fecha_emision));

                $fechaInicioObj = strtotime($factura->mes_facturado);
                $fechaFinObj = strtotime(date('t-m-Y', $fechaInicioObj));

                $factura->periodo_inicio = date('d-m-Y', $fechaInicioObj);
                $factura->periodo_fin = date('d-m-Y', $fechaFinObj);
            }

           
           

        
            $router->render('admin/registrados/registrado',[
                'titulo'=>$registrado->nombre.' '.$registrado->apellido,
                'registrado'=>$registrado,
               
                'facturas'=>$facturas
            ]);

        }
    }