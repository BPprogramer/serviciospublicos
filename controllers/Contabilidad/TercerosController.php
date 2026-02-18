<?php

namespace Controllers\Contabilidad;

use Model\Contabilidad\Tercero;
use Model\Factura;
use Model\Registrado;

use MVC\Router;

class TercerosController
{
    public static function index(Router $router)
    {



        $router->render('admin/contabilidad/terceros/index', [
            'titulo' => 'Terceros'
        ]);
    }
    public static function crear(Router $router)
    {
        $alertas = [];
        $tercero = new Tercero();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tercero->sincronizar($_POST);
            $alertas = $tercero->validar();

            if (empty($alertas)) {
                $resultado = $tercero->guardar();
                if ($resultado) {
                    $tercero = new Tercero([]);
                    Tercero::setAlerta('exito', 'Tercero creado correctamente, redireccionando');
                    $alertas = Tercero::getAlertas();
                }
            }
        }

        $router->render('admin/contabilidad/terceros/crear', [
            'titulo' => 'Crear Tercero',
            'tercero' => $tercero,
            'alertas' => $alertas
        ]);
    }
    public static function editar(Router $router)
    {
        $alertas = [];

        // obtener id
        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location:/admin/contabilidad/terceros');
            return;
        }

        // buscar tercero
        $tercero = Tercero::find($id);

        if (!$tercero) {
            header('Location:/admin/contabilidad/terceros');
            return;
        }

        // POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $tercero->sincronizar($_POST);
            $alertas = $tercero->validar();

            if (empty($alertas)) {

                $resultado = $tercero->guardar();

                if ($resultado) {
                    Tercero::setAlerta('exito', 'Tercero actualizado correctamente, redireccionando');
                    $alertas = Tercero::getAlertas();
                }
            }
        }

        // render
        $router->render('admin/contabilidad/terceros/editar', [
            'titulo' => 'Editar Tercero',
            'tercero' => $tercero,
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
            header('Location:/admin/contabilidad/terceros');
            return;
        }

        // buscar tercero
        $tercero = Tercero::find($id);

        if (!$tercero) {
            header('Location:/admin/contabilidad/terceros');
            return;
        }

        // formateos bonitos (tipo persona)
        $tercero->tipo_persona = ucfirst($tercero->tipo_persona);

        // render vista
        $router->render('admin/contabilidad/terceros/ver', [
            'titulo'  => $tercero->nombre,
            'tercero' => $tercero
        ]);
    }
}
