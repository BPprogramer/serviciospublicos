<?php

namespace Controllers\Contabilidad;

use Model\Contabilidad\Cuenta;
use MVC\Router;

class CuentasController
{

    // =========================
    // LISTADO
    // =========================
    public static function index(Router $router)
    {
        $router->render('admin/contabilidad/cuentas/index', [
            'titulo' => 'Plan de Cuentas'
        ]);
    }


    // =========================
    // CREAR
    // =========================
    public static function crear(Router $router)
    {
        $alertas = [];
        $cuenta = new Cuenta();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $cuenta->sincronizar($_POST);
            $alertas = $cuenta->validar();

            if (empty($alertas)) {
                $resultado = $cuenta->guardar();

                if ($resultado) {
                    $cuenta = new Cuenta([]);
                    Cuenta::setAlerta('exito', 'Cuenta creada correctamente, redireccionando');
                    $alertas = Cuenta::getAlertas();
                }
            }
        }

        $router->render('admin/contabilidad/cuentas/crear', [
            'titulo' => 'Crear Cuenta',
            'cuenta' => $cuenta,
            'alertas' => $alertas
        ]);
    }


    // =========================
    // EDITAR
    // =========================
    public static function editar(Router $router)
    {
        $alertas = [];

        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location:/admin/contabilidad/cuentas');
            return;
        }

        $cuenta = Cuenta::find($id);

        if (!$cuenta) {
            header('Location:/admin/contabilidad/cuentas');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $cuenta->sincronizar($_POST);
            $alertas = $cuenta->validar();

            if (empty($alertas)) {

                $resultado = $cuenta->guardar();

                if ($resultado) {
                    Cuenta::setAlerta('exito', 'Cuenta actualizada correctamente, redireccionando');
                    $alertas = Cuenta::getAlertas();
                }
            }
        }

        $router->render('admin/contabilidad/cuentas/editar', [
            'titulo' => 'Editar Cuenta',
            'cuenta' => $cuenta,
            'alertas' => $alertas
        ]);
    }


    // =========================
    // VER
    // =========================
    public static function ver(Router $router)
    {
        if (!is_auth()) {
            header('Location:/login');
        }

        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location:/admin/contabilidad/cuentas');
            return;
        }

        $cuenta = Cuenta::find($id);

        if (!$cuenta) {
            header('Location:/admin/contabilidad/cuentas');
            return;
        }

        // Formatear tipo bonito
        $cuenta->tipo = ucfirst($cuenta->tipo);

        $router->render('admin/contabilidad/cuentas/ver', [
            'titulo' => $cuenta->nombre,
            'cuenta' => $cuenta
        ]);
    }
}
