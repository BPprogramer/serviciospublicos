<?php 
    namespace Controllers;
    use Model\Estrato;

    class ApiEstratos{
        public static function index(){
            $estratos = Estrato::all();
          
            $i=0;
            $datoJson = '{
            "data": [';
                foreach($estratos as $key=>$estrato){
                    $i++;
 
                    $acciones = "<div class='table__td--acciones'>";
                    $acciones .= "<a class='table__accion table__accion--editar' href='/admin/estratos/editar?id=$estrato->id'><i class='fa-solid fa-pen'></i></a> ";
                    $acciones .= "<button  class='table__accion table__accion--eliminar' id='btn_eliminar_estrato' data-estrato-id='$estrato->id'><i class='fa-solid fa-trash'></i></button>";
                    $acciones .= "<button  class='table__accion table__accion--info' id='btn_info_estrato' data-estrato-id='$estrato->id'><i class='fa-solid fa-search'></i></button>";

                    $acciones .= "</div>";

                    $tarifa_plena = number_format($estrato->tarifa_plena);
                    $subsidio = number_format($estrato->subsidio);
                    $copago = number_format($estrato->copago);
                
       
                    $datoJson.= '[
                            "'.$i.'",
                            "'.$estrato->estrato.'",
                            "'.$estrato->year.'",
                         
                            
                            "$'.$tarifa_plena.'",
                            "$'.$subsidio.'",
                    
                            "$'.$copago.'",
                            "'.$acciones.'"
                    ]';
                    if($key != count($estratos)-1){
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
            $estrato = Estrato::find($id);
            if(!isset($estrato)){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Estrato no encontrado, porfavor intente nuevamente']);
                return;
            }
            $resultado = $estrato->eliminar();
            if(!$resultado){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un error, porfavor Intenta Nuevamente']);
                return;
            }
            echo json_encode(['tipo'=>'success', 'mensaje'=>'Estrato eliminado correctamente']);
            
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