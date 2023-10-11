<?php 

    namespace Controllers;

    use Model\Usuario;

    class ApiUsuarios{
        public static function index(){
        
           $usuarios_todos = Usuario::all();
           $usuarios = array_filter($usuarios_todos, function($usuario){
               if($usuario->id!=1 ){
                   return $usuario;
               }
         
           });
          
           $i=0;
           $datoJson = '{
            "data": [';
                foreach($usuarios as $key=>$usuario){
                    $i++;
 
                    $nombreCompleto = $usuario->nombre.' '.$usuario->apellido;
                    $acciones = "<div class='table__td--acciones'>";
                    $acciones .= "<a class='table__accion table__accion--editar' href='/admin/usuarios/editar?id=$usuario->id'><i class='fa-solid fa-pen'></i></a> ";
                    $acciones .= "<button  class='table__accion table__accion--eliminar' id='btn_eliminar_usuario' data-usuario-id='$usuario->id'><i class='fa-solid fa-trash'></i></button>";
                    //$acciones .= "<button  class='table__accion table__accion--eliminar' id='btn_eliminar_usuario' data-usuario-id='$usuario->id'><i class='fa-solid fa-circle-xmark'></i>Eliminar</button>";
                    $acciones .= "</div>";

                    $roll = '';
                    if($usuario->admin==1){
                        $roll = 'Aministrador';
                    }else{
                        $roll = 'Facturador';
                    }
                    

                    if($usuario->confirmado==1){
                        $estado = "<span class='table__td--activo' id='btn_cambiar_estado' data-estado-actual='$usuario->confirmado'  data-usuario-id='$usuario->id'>Activo</span>";
                    }else{
                        $estado = "<span class='table__td--inactivo' id='btn_cambiar_estado' data-estado-actual='$usuario->confirmado'  data-usuario-id='$usuario->id'>Ináctivo</span>";
                    }
                  
                    
                    $datoJson.= '[
                            "'.$i.'",
                            "'.$nombreCompleto.'",
                            "'.$usuario->email.'",
                         
                            
                            "'.$estado.'",
                            "'.$roll.'",
                    
                            "'.$acciones.'"
                    ]';
                    if($key != count($usuarios)-1){
                        $datoJson.=",";
                    }
                }
      
            $datoJson.=  ']}';
           echo $datoJson;
          
        }
        
        public static function editar_estado(){
            $id = $_GET['id'];
          
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if(!$id){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un error, porfavor Intenta Nuevamente']);
                return;

            }
            
            $usuario = Usuario::find($id);
   
            if(!isset($usuario)){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Usuario no encontrado, porfavor intente nuevamente']);
                return;
            }
            $mensaje = '';
            
            if($usuario->confirmado==0){
                $usuario->confirmado=1;
                $mensaje = 'Activado';
            }else{
                $usuario->confirmado=0;
                $mensaje = 'Desactivado';
            }
        
          
            $resultado = $usuario->guardar();
            if(!$resultado){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un error, porfavor Intenta Nuevamente']);
                return;
            }
            echo json_encode(['tipo'=>'success', 'mensaje'=>'Usuario '.$mensaje.' correctamente']);
        }

        public static function eliminar(){
            $id = $_GET['id'];
            
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if(!$id){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un error, porfavor Intenta Nuevamente']);
                return;

            }
            $usuario = Usuario::find($id);
            if(!isset($usuario)){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Usuario no encontrado, porfavor intente nuevamente']);
                return;
            }
            $resultado = $usuario->eliminar();
            if(!$resultado){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un error, porfavor Intenta Nuevamente']);
                return;
            }
            echo json_encode(['tipo'=>'success', 'mensaje'=>'Usuario eliminado correctamente']);
            
         }
    }