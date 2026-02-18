<?php

namespace Controllers\Contabilidad;

use MVC\Router;
use Model\Contabilidad\EgresoSimple;
use Model\Contabilidad\CuentaBancaria;
use Model\Usuario;

class EgresoSimpleController
{

    // =====================================
    // INDEX
    // =====================================
    public static function index(Router $router)
    {
        $router->render('admin/contabilidad/egresos_simples/index', [
            'titulo' => 'Egresos Simples'
        ]);
    }


    // =====================================
    // CREAR
    // =====================================
    public static function crear(Router $router)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['id'])) {
            header('Location:/login');
            exit;
        }

        $alertas = [];
        $egreso = new EgresoSimple();
        $egreso->user_id = $_SESSION['id'];

        $cuentasBancarias = CuentaBancaria::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $egreso->sincronizar($_POST);
            $alertas = $egreso->validar();

            if (empty($alertas)) {

                $resultado = $egreso->guardar();

                if ($resultado['resultado']) {

                    $egreso = new EgresoSimple();
                    $egreso->user_id = $_SESSION['id'];

                    EgresoSimple::setAlerta('exito', 'Egreso simple creado correctamente');
                } else {
                    EgresoSimple::setAlerta('error', 'Error al guardar el egreso');
                }

                $alertas = EgresoSimple::getAlertas();
            }
        }

        $router->render('admin/contabilidad/egresos_simples/crear', [
            'titulo' => 'Crear Egreso Simple',
            'egreso' => $egreso,
            'cuentasBancarias' => $cuentasBancarias,
            'alertas' => $alertas
        ]);
    }


    // =====================================
    // EDITAR
    // =====================================
    public static function editar(Router $router)
    {
        if (!is_auth()) {
            header('Location:/login');
            return;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['id'])) {
            header('Location:/login');
            exit;
        }

        $alertas = [];

        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location:/admin/contabilidad/egresos-simples');
            return;
        }

        $egreso = EgresoSimple::find($id);

        if (!$egreso) {
            header('Location:/admin/contabilidad/egresos-simples');
            return;
        }

        // 🔒 NO PERMITIR EDITAR SI ESTÁ ANULADO
        if ($egreso->anulado) {
            EgresoSimple::setAlerta('error', 'No se puede editar un egreso anulado');
            $alertas = EgresoSimple::getAlertas();
        }

        $cuentasBancarias = CuentaBancaria::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$egreso->anulado) {

            $egreso->sincronizar($_POST);
            $alertas = $egreso->validar();

            if (empty($alertas)) {
                $egreso->user_id = $_SESSION['id'];
                $resultado = $egreso->guardar();

                if ($resultado) {
                    EgresoSimple::setAlerta('exito', 'Egreso simple actualizado correctamente');
                } else {
                    EgresoSimple::setAlerta('error', 'Error al actualizar');
                }

                $alertas = EgresoSimple::getAlertas();
            }
        }

        $router->render('admin/contabilidad/egresos_simples/editar', [
            'titulo' => 'Editar Egreso Simple',
            'egreso' => $egreso,
            'cuentasBancarias' => $cuentasBancarias,
            'alertas' => $alertas
        ]);
    }


    // =====================================
    // VER
    // =====================================
    public static function ver(Router $router)
    {
        if (!is_auth()) {
            header('Location:/login');
            return;
        }

        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location:/admin/contabilidad/egresos-simples');
            return;
        }

        $egreso = EgresoSimple::find($id);

        if (!$egreso) {
            header('Location:/admin/contabilidad/egresos-simples');
            return;
        }

        $cuentaBancaria = CuentaBancaria::find($egreso->cuenta_bancaria_id);
        $responsable = Usuario::find($egreso->user_id);

        $router->render('admin/contabilidad/egresos_simples/ver', [
            'titulo' => 'Egreso Simple',
            'egreso' => $egreso,
            'cuentaBancaria' => $cuentaBancaria,
            'responsable' => $responsable
        ]);
    }
}
