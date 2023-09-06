<?php 

    namespace Controllers;


use Model\Ponente;
use Model\Usuario;
    use MVC\Router;



    class UsuariosController{
        public static function index(Router $router){
            
          
            $alertas = [];
    
            $router->render('admin/usuarios/index',[
                'titulo'=>'Usuarios',
                
                'alertas'=>$alertas
            ]);
        }

        public static function crear(Router $router){
          
            if(!is_admin()){
                header('Location:/login');
            }

   
            $alertas = [];
            $usuario = new Usuario();
            if($_SERVER['REQUEST_METHOD']=='POST'){
        
                $usuario->sincronizar($_POST);
           
                $alertas = $usuario->validar_cuenta();

                if(empty($alertas)){
                    $existeUsuario = Usuario::where('email',$usuario->email);
                    if($existeUsuario){
                        Usuario::setAlerta('error', 'el Usuario Y esta registrado');
                        $alertas = Usuario::getAlertas();
                    }else{

                        //hashear el apssword
                        $usuario->hashPassword();
                        //eliminar password 2
                        unset($usuario->password2);
                        $resultado = $usuario->guardar();
                
                        if($resultado){
                            $usuario = new Usuario([]);
                            Usuario::setAlerta('exito', 'creado exitosamente, Redireccionando');
                            $alertas = Usuario::getAlertas();
                        } 
                    }
                }
            }
            // $redes = json_decode($ponente->redes);
   
           
            $router->render('admin/usuarios/crear',[
                'titulo'=>'Registrar Usuario',
                'alertas'=>$alertas,
                'usuario'=>$usuario
            ]);
        }

        public static function editar(Router $router){
             
            if(!is_admin()){
                header('Location:/login');
            }

            $alertas = [];
          
            $id =$_GET['id'];
            $id = filter_var($id,FILTER_VALIDATE_INT);
            if(!$id){
                header('Location:/admin/usuarios');
            }

            //obtener usuario a editar
            $usuario = Usuario::find($id);
            $passwordAnterior = $usuario->password;
           
         
            if(!$usuario){
                header('Location:/admin/usuarios');
            }  
            if($_SERVER['REQUEST_METHOD']=='POST'){
                 
                $passwordNuevo = $_POST['password'];
                $usuario->sincronizar($_POST);
                
                
                if($_POST['password'] || $_POST['password2']){
                    $alertas = $usuario->validar_cuenta();
                    if(empty($alertas)){
                        $usuario->hashPassword();
                    }
                    
                }else{
                    $alertas = $usuario->validar_cuenta_editar();
                    if(empty($alertas)){
                        $usuario->password = $passwordAnterior ;
                    }
                    
                }
                

               
                
      
                if(empty($alertas)){
                    
                   
                    $resultado = $usuario->guardar();
                    if($resultado ){
                        $usuario = new Usuario([]);
                        Usuario::setAlerta('exito', 'actualizado exitosamente, Redireccionando');
                        $alertas = Usuario::getAlertas();
                    }
                }
            }

            $router->render('admin/usuarios/editar',[
                'titulo'=>'Actualizar Usuario',
                'usuario'=>$usuario??null,
                'alertas'=>$alertas
            ]);
        }

    }