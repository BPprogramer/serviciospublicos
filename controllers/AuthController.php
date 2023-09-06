<?php 

    namespace Controllers;
    use MVC\Router;
    use Model\Usuario;
    use Model\EmailRegistro;
    use Classes\Email;

    class AuthController{

        public static function index(){
            header('Location:/servicios/login');
        }
        public static function login(Router $router){
            
            $alertas = [];
            if($_SERVER['REQUEST_METHOD']=='POST'){
                
                $auth = new Usuario($_POST);
                $alertas = $auth->validarLogin();
 
               
                if(empty($alertas)){
                    //verificar que el usuario existe
                    $usuario = Usuario::where('email', $auth->email);
                    if(!$usuario || !$usuario->confirmado){
                        Usuario::setAlerta('error', 'el usuario no existe o no está confirmado');
                    }else{
                        //el usuario existe y esta confirmado
                        if(password_verify($_POST['password'], $usuario->password)){
                            //inisiar sesion
                            session_start();
                            $_SESSION['id'] = $usuario->id;
                            $_SESSION['nombre'] = $usuario->nombre;
                            $_SESSION['email'] = $usuario->email;
                            $_SESSION['login'] = true;
                            
                            //redireccion
                            if($usuario->admin){
                                $_SESSION['admin'] = $usuario->admin ??true;
                                
                            }else{
                                
                               $_SESSION['admin'] = false;

                            }
                           
                            header('Location:/servicios/admin/dashboard');
                            
                     
                           // header('Location:/dashboard');
                        }else{
                            Usuario::setAlerta('error', 'Password incorrecto');
                        }
                    }
                 
                }
            }
            $alertas = Usuario::getAlertas();
            $router->render('auth/login',[
                'titulo'=> 'Iniciar Sesión', 
                'alertas'=>$alertas
            ]);
        }
        public static function logout(){
            session_start();
            $_SESSION = [];
            header('Location:/servicios/login');

           
        }

     

    }