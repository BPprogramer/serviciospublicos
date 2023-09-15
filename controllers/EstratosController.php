<?php 

    namespace Controllers;
    use MVC\Router;
    use Model\Estrato;

    class EstratosController{
        public static function index(Router $router){
            
            $router->render('admin/estratos/index',[
                'titulo'=>'estratos'
            ]);
        }
        public static function crear(Router $router){
            
            $alertas = [];
            $estratos = new Estrato();
            

            if($_SERVER['REQUEST_METHOD']=='POST'){

                debuguear($_POST);
                
             
                $estratos->sincronizar($_POST);
                $estratos->validarAjuste();

   
                $alertas = $estratos->validar();
                if(empty($alertas)){
            
                    $estratos->formatearDatosFloat();
                    $resultado =  $estratos->guardar();
                    if($resultado){
                        $estratos = new Estrato([]);
                        Estrato::setAlerta('exito', 'creado exitosamente, Redireccionando');
                        $alertas = Estrato::getAlertas();
                    }
                }
                
            }
            
          
            $router->render('admin/estratos/crear',[
                'titulo'=>'Agregar un Estrato',
                'alertas'=>$alertas,
                'estratos'=>$estratos,
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