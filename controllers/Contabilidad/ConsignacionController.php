<?php

namespace Controllers\Contabilidad;

use MVC\Router;
use Model\Contabilidad\Consignacion;
use Model\Contabilidad\CuentaBancaria;
use Model\Usuario;

class ConsignacionController
{

    // =====================================
    // INDEX
    // =====================================
    public static function index(Router $router)
    {
        $router->render('admin/contabilidad/consignaciones/index', [
            'titulo' => 'Consignaciones'
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
        $consignacion = new Consignacion();
        $consignacion->usuario_id = $_SESSION['id'];

        $cuentasBancarias = CuentaBancaria::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $consignacion->sincronizar($_POST);
            $consignacion->usuario_id = $_SESSION['id'];

            // =========================
            // VALIDAR MODELO
            // =========================
            $alertas = $consignacion->validar();

            // =========================
            // PROCESAR PDF
            // =========================
            if (!empty($_FILES['ruta_comprobante']['name'])) {

                $archivo = $_FILES['ruta_comprobante'];

                if ($archivo['error'] === 0) {

                    if ($archivo['type'] !== 'application/pdf') {
                        Consignacion::setAlerta('error', 'El comprobante debe ser un PDF');
                    }

                    if ($archivo['size'] > 5 * 1024 * 1024) {
                        Consignacion::setAlerta('error', 'El PDF no puede superar 5MB');
                    }

                    $alertas = Consignacion::getAlertas();

                    if (empty($alertas)) {

                        $carpeta = $_SERVER['DOCUMENT_ROOT'] . '/storage/comprobantes/consignaciones/';

                        if (!is_dir($carpeta)) {
                            mkdir($carpeta, 0755, true);
                        }

                        $nombreArchivo = 'consignacion_' . time() . '.pdf';
                        $rutaCompleta = $carpeta . $nombreArchivo;

                        move_uploaded_file($archivo['tmp_name'], $rutaCompleta);

                        $consignacion->ruta_comprobante = $nombreArchivo;
                    }
                }
            }

            if (empty(Consignacion::getAlertas())) {

                $resultado = $consignacion->guardar();

                if ($resultado['resultado']) {

                    $consignacion = new Consignacion();
                    $consignacion->usuario_id = $_SESSION['id'];

                    Consignacion::setAlerta('exito', 'Consignación creada correctamente');
                } else {
                    Consignacion::setAlerta('error', 'Error al guardar la consignación');
                }

                $alertas = Consignacion::getAlertas();
            }
        }

        $router->render('admin/contabilidad/consignaciones/crear', [
            'titulo' => 'Crear Consignación',
            'consignacion' => $consignacion,
            'cuentasBancarias' => $cuentasBancarias,
            'alertas' => $alertas
        ]);
    }


    // =====================================
    // EDITAR
    // =====================================
    public static function editar(Router $router)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['id'])) {
            header('Location:/login');
            exit;
        }

        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location:/admin/contabilidad/consignaciones');
            return;
        }

        $consignacion = Consignacion::find($id);

        if (!$consignacion) {
            header('Location:/admin/contabilidad/consignaciones');
            return;
        }

        if ($consignacion->anulado) {
            Consignacion::setAlerta('error', 'No se puede editar una consignación anulada');
        }

        $cuentasBancarias = CuentaBancaria::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$consignacion->anulado) {

            $consignacion->sincronizar($_POST);
            $consignacion->usuario_id = $_SESSION['id'];

            $alertas = $consignacion->validar();

            // =========================
            // NUEVO PDF
            // =========================
            if (!empty($_FILES['ruta_comprobante']['name'])) {

                $archivo = $_FILES['ruta_comprobante'];

                if ($archivo['error'] === 0) {

                    if ($archivo['type'] !== 'application/pdf') {
                        Consignacion::setAlerta('error', 'El comprobante debe ser un PDF');
                    }

                    if ($archivo['size'] > 5 * 1024 * 1024) {
                        Consignacion::setAlerta('error', 'El PDF no puede superar 5MB');
                    }

                    $alertas = Consignacion::getAlertas();

                    if (empty($alertas)) {

                        $carpeta = $_SERVER['DOCUMENT_ROOT'] . '/storage/comprobantes/consignaciones/';

                        if (!is_dir($carpeta)) {
                            mkdir($carpeta, 0755, true);
                        }

                        // Eliminar archivo anterior si existe
                        if (!empty($consignacion->ruta_comprobante)) {
                            $rutaAnterior = $carpeta . $consignacion->ruta_comprobante;
                            if (file_exists($rutaAnterior)) {
                                unlink($rutaAnterior);
                            }
                        }

                        $nombreArchivo = 'consignacion_' . time() . '.pdf';
                        $rutaCompleta = $carpeta . $nombreArchivo;

                        move_uploaded_file($archivo['tmp_name'], $rutaCompleta);

                        $consignacion->ruta_comprobante = $nombreArchivo;
                    }
                }
            }

            if (empty(Consignacion::getAlertas())) {

                $resultado = $consignacion->guardar();

                if ($resultado) {
                    Consignacion::setAlerta('exito', 'Consignación actualizada correctamente');
                } else {
                    Consignacion::setAlerta('error', 'Error al actualizar');
                }
            }
        }

        $alertas = Consignacion::getAlertas();

        $router->render('admin/contabilidad/consignaciones/editar', [
            'titulo' => 'Editar Consignación',
            'consignacion' => $consignacion,
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
            header('Location:/admin/contabilidad/consignaciones');
            return;
        }

        $consignacion = Consignacion::find($id);

        if (!$consignacion) {
            header('Location:/admin/contabilidad/consignaciones');
            return;
        }

        $cuentaBancaria = CuentaBancaria::find($consignacion->cuenta_bancaria_id);
        $responsable = Usuario::find($consignacion->usuario_id);

        $router->render('admin/contabilidad/consignaciones/ver', [
            'titulo' => $consignacion->id,
            'consignacion' => $consignacion,
            'cuentaBancaria' => $cuentaBancaria,
            'responsable' => $responsable
        ]);
    }
}
