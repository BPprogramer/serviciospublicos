<?php 
    namespace Controllers;

use Model\Estrato;
use Model\Registrado;

    class ApiRegistrados{
        public static function index(){
            $registrados = Registrado::all();
         
      
     
            $i=0;
            $datoJson = '{
             "data": [';
                 foreach($registrados as $key=>$registrado){
                     $i++;

                     $nombreEstrado = 'Estrato Eliminado';
                     if($registrado->estrato_id){
                        
                        $estrato = Estrato::find($registrado->estrato_id);
                        $nombreEstrado = $estrato->estrato;
                       
                     }
                    
              
                     $nombreCompleto = $registrado->nombre.' '.$registrado->apellido;
                    
                     $acciones = "<div class='table__td--acciones'>";
                     $acciones .= "<a class='table__accion table__accion--editar' href='/admin/registrados/editar?id=$registrado->id'><i class='fa-solid fa-pen'></i></a> ";
                     $acciones .= "<button  class='table__accion table__accion--eliminar' id='btn_eliminar_registrado' data-registrado-id='$registrado->id'><i class='fa-solid fa-trash'></i></button>";
                     $acciones .= "<a href='/admin/registrados/registrado?id=$registrado->id' class='table__accion table__accion--info' id='btn_info_registrado' data-registrado-id='$registrado->id'><i class='fa-solid fa-search'></a>";
                     $acciones .= "</div>";
 
                  
 
                     if($registrado->facturas > $estrato->facturas_vencidas){
                         $estado = "<span class='table__td--inactivo'>Vencido</span>";
                     }else{
                         $estado = "<span class='table__td--activo' >Vigente</span>";
                     }
                   
                     
                     $datoJson.= '[
                             "'.$i.'",
                             "'.$nombreCompleto.'",
                             "'.$registrado->cedula_nit.'",
                          
                          
                             "'.$registrado->direccion.'",
                             "'.$nombreEstrado.'",
                             "'.$estado.'",
                          
        
                     
                             "'.$acciones.'"
                     ]';
                     if($key != count($registrados)-1){
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
            $registrado = Registrado::find($id);
            if(!isset($registrado)){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Subscriptor no encontrado, porfavor intente nuevamente']);
                return;
            }
            $resultado = $registrado->eliminar();
            if(!$resultado){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un error, porfavor Intenta Nuevamente']);
                return;
            }
            echo json_encode(['tipo'=>'success', 'mensaje'=>'Subscriptor eliminado correctamente']);
            
         }

       
    }