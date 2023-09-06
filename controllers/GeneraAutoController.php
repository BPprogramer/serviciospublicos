<?php 
    namespace Controllers;

use Model\GeneraAuto;

    class GeneraAutoController{
        public static function generarAuto(){
            $auto = new GeneraAuto();
            if($_SERVER['REQUEST_METHOD']=='POST'){
           
                $auto->sincronizar($_POST);
                $resutlado = $auto->guardar();
                echo json_encode($resutlado);
                return;            
            }

            $auto = GeneraAuto::find(('id'));
            echo json_encode($auto);
            
        }
    }