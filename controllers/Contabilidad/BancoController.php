<?php

namespace Controllers\Contabilidad;

use Model\Contabilidad\Banco;
use MVC\Router;

class BancoController
{
    public static function index(Router $router)
    {

        $router->render('admin/contabilidad/bancos/index', [
            'titulo' => 'Bancos'
        ]);
    }

    public static function crear(Router $router)
    {
        $alertas = [];
        $banco = new Banco();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $banco->sincronizar($_POST);
            $alertas = $banco->validar();

            if (empty($alertas)) {

                $resultado = $banco->guardar();

                if ($resultado) {
                    $banco = new Banco([]);
                    Banco::setAlerta('exito', 'Banco creado correctamente, redireccionando');
                    $alertas = Banco::getAlertas();
                }
            }
        }

        $router->render('admin/contabilidad/bancos/crear', [
            'titulo' => 'Crear Banco',
            'banco' => $banco,
            'alertas' => $alertas
        ]);
    }

    public static function editar(Router $router)
    {
        $alertas = [];

        // obtener id
        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location:/admin/contabilidad/bancos');
            return;
        }

        // buscar banco
        $banco = Banco::find($id);

        if (!$banco) {
            header('Location:/admin/contabilidad/bancos');
            return;
        }

        // POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $banco->sincronizar($_POST);
            $alertas = $banco->validar();

            if (empty($alertas)) {

                $resultado = $banco->guardar();

                if ($resultado) {
                    Banco::setAlerta('exito', 'Banco actualizado correctamente, redireccionando');
                    $alertas = Banco::getAlertas();
                }
            }
        }

        $router->render('admin/contabilidad/bancos/editar', [
            'titulo' => 'Editar Banco',
            'banco' => $banco,
            'alertas' => $alertas
        ]);
    }

    public static function ver(Router $router)
    {
        if (!is_auth()) {
            header('Location:/login');
        }

        // validar id
        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location:/admin/contabilidad/bancos');
            return;
        }

        // buscar banco
        $banco = Banco::find($id);

        if (!$banco) {
            header('Location:/admin/contabilidad/bancos');
            return;
        }

        $router->render('admin/contabilidad/bancos/ver', [
            'titulo'  => $banco->nombre,
            'banco'   => $banco
        ]);
    }
}
