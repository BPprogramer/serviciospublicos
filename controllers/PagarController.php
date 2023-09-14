<?php 
    namespace Controllers;

use MVC\Router;

    class PagarController{
        public static function index(Router $router){
            $router->render('admin/pagar/index',[
                'titulo'=>'Relizar Pagos'
            ]);
        }
        public static function pagos(Router $router){
            $router->render('admin/pagar/pagos',[
                'titulo'=>'Pagos Registrados'
            ]);
        }
    }