<?php 

    namespace Controllers;

use MVC\Router;

    class EmitidasController{
        public static function index(Router $router){
            $router->render('admin/emitidas/index',[
                'titulo'=>'Facturas Pendientes del mes'
            ]);
        }
    }
