<?php

namespace Controllers\Contabilidad;

use MVC\Router;
use Model\Contabilidad\Egreso;
use Model\Contabilidad\EgresoCuenta;
use Model\Contabilidad\CuentaBancaria;
use Model\Contabilidad\Tercero;
use Model\Contabilidad\Cuenta;

class EgresoController
{

    // =====================================
    // INDEX
    // =====================================
    public static function index(Router $router)
    {
        $router->render('admin/contabilidad/egresos/index', [
            'titulo' => 'Egresos'
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
        $egreso = new Egreso();
        $egreso->user_id = $_SESSION['id'];

        $terceros = Tercero::all();
        $cuentasBancarias = CuentaBancaria::all();
        $cuentas = Cuenta::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // =========================
            // 1️⃣ Validar modelo principal
            // =========================
            $egreso->sincronizar($_POST);
            $alertas = $egreso->validar();

            if (!empty($alertas)) {
                $router->render('admin/contabilidad/egresos/crear', [
                    'titulo' => 'Crear Egreso',
                    'egreso' => $egreso,
                    'terceros' => $terceros,
                    'cuentasBancarias' => $cuentasBancarias,
                    'cuentas' => $cuentas,
                    'alertas' => $alertas
                ]);
                return;
            }

            // =========================
            // 2️⃣ Validar líneas contables
            // =========================
            $lineas = $_POST['cuentas'] ?? [];
            $totalDebito = 0;
            $totalCredito = 0;
            $objetosLineas = [];
            $cuentasUsadas = [];

            if (empty($lineas)) {
                Egreso::setAlerta('error', 'Debe agregar al menos una línea contable');
            }

            foreach ($lineas as $linea) {

                $egresoCuenta = new EgresoCuenta($linea);
                $erroresLinea = $egresoCuenta->validar();

                if (!empty($erroresLinea)) {
                    foreach ($erroresLinea as $tipo => $mensajes) {
                        foreach ($mensajes as $mensaje) {
                            Egreso::setAlerta($tipo, $mensaje);
                        }
                    }
                }

                // Validar cuenta repetida
                if (!empty($egresoCuenta->cuenta_id)) {
                    if (in_array($egresoCuenta->cuenta_id, $cuentasUsadas)) {
                        Egreso::setAlerta('error', 'No se puede repetir la misma cuenta contable');
                    } else {
                        $cuentasUsadas[] = $egresoCuenta->cuenta_id;
                    }
                }

                $totalDebito += (float)$egresoCuenta->debito;
                $totalCredito += (float)$egresoCuenta->credito;

                $objetosLineas[] = $egresoCuenta;
            }

            if ($totalDebito <= 0) {
                Egreso::setAlerta('error', 'El total débito debe ser mayor a 0');
            }

            if ($totalDebito != $totalCredito) {
                Egreso::setAlerta('error', 'El asiento contable no cuadra');
            }

            $alertas = Egreso::getAlertas();

            if (!empty($alertas)) {
                $router->render('admin/contabilidad/egresos/crear', [
                    'titulo' => 'Crear Egreso',
                    'egreso' => $egreso,
                    'terceros' => $terceros,
                    'cuentasBancarias' => $cuentasBancarias,
                    'cuentas' => $cuentas,
                    'alertas' => $alertas
                ]);
                return;
            }

            // =========================
            // 3️⃣ Guardar con transacción
            // =========================
            try {

                Egreso::beginTransaction();

                $ultimo = Egreso::ultimoConsecutivo();
                $egreso->consecutivo = $ultimo + 1;

                $resultado = $egreso->guardar();

                if (!$resultado['resultado']) {
                    throw new \Exception("Error al guardar egreso");
                }

                $egreso->id = $resultado['id'];

                foreach ($objetosLineas as $linea) {
                    $linea->egreso_id = $egreso->id;
                    $linea->guardar();
                }

                Egreso::commit();

                $egreso = new Egreso([]);
                $egreso->user_id = $_SESSION['id'];

                Egreso::setAlerta('exito', 'Egreso creado correctamente');
                $alertas = Egreso::getAlertas();
            } catch (\Exception $e) {

                Egreso::rollback();
                Egreso::setAlerta('error', 'Error interno al guardar');
                $alertas = Egreso::getAlertas();
            }
        }

        $router->render('admin/contabilidad/egresos/crear', [
            'titulo' => 'Crear Egreso',
            'egreso' => $egreso,
            'terceros' => $terceros,
            'cuentasBancarias' => $cuentasBancarias,
            'cuentas' => $cuentas,
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
        $alertas = [];

        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location:/admin/contabilidad/egresos');
            return;
        }

        $egreso = Egreso::find($id);

        if (!$egreso) {
            header('Location:/admin/contabilidad/egresos');
            return;
        }

        $lineas = EgresoCuenta::whereArray([
            'egreso_id' => $egreso->id
        ]);

        $terceros = Tercero::all();
        $cuentasBancarias = CuentaBancaria::all();
        $cuentas = Cuenta::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if ($egreso->anulado) {
                Egreso::setAlerta('error', 'No se puede editar un egreso anulado');
                $alertas = Egreso::getAlertas();
            } else {

                // 1️⃣ Validar modelo principal
                $egreso->sincronizar($_POST);
                $alertas = $egreso->validar();

                if (!empty($alertas)) {
                    $router->render('admin/contabilidad/egresos/editar', [
                        'titulo' => 'Editar Egreso',
                        'egreso' => $egreso,
                        'lineas' => $lineas,
                        'terceros' => $terceros,
                        'cuentasBancarias' => $cuentasBancarias,
                        'cuentas' => $cuentas,
                        'alertas' => $alertas
                    ]);
                    return;
                }

                // 2️⃣ Validar líneas
                $lineasPost = $_POST['cuentas'] ?? [];
                $totalDebito = 0;
                $totalCredito = 0;
                $objetosLineas = [];
                $cuentasUsadas = [];

                foreach ($lineasPost as $linea) {

                    $egresoCuenta = new EgresoCuenta($linea);
                    $erroresLinea = $egresoCuenta->validar();

                    if (!empty($erroresLinea)) {
                        foreach ($erroresLinea as $tipo => $mensajes) {
                            foreach ($mensajes as $mensaje) {
                                Egreso::setAlerta($tipo, $mensaje);
                            }
                        }
                    }

                    if (!empty($egresoCuenta->cuenta_id)) {
                        if (in_array($egresoCuenta->cuenta_id, $cuentasUsadas)) {
                            Egreso::setAlerta('error', 'No se puede repetir la misma cuenta contable');
                        } else {
                            $cuentasUsadas[] = $egresoCuenta->cuenta_id;
                        }
                    }

                    $totalDebito += (float)$egresoCuenta->debito;
                    $totalCredito += (float)$egresoCuenta->credito;

                    $objetosLineas[] = $egresoCuenta;
                }

                if ($totalDebito <= 0) {
                    Egreso::setAlerta('error', 'El total débito debe ser mayor a 0');
                }

                if ($totalDebito != $totalCredito) {
                    Egreso::setAlerta('error', 'El asiento contable no cuadra');
                }

                $alertas = Egreso::getAlertas();

                if (!empty($alertas)) {
                    $router->render('admin/contabilidad/egresos/editar', [
                        'titulo' => 'Editar Egreso',
                        'egreso' => $egreso,
                        'lineas' => $lineas,
                        'terceros' => $terceros,
                        'cuentasBancarias' => $cuentasBancarias,
                        'cuentas' => $cuentas,
                        'alertas' => $alertas
                    ]);
                    return;
                }

                // 3️⃣ Transacción
                try {

                    Egreso::beginTransaction();
                    $egreso->user_id = $_SESSION['id'];
                    $egreso->guardar();

                    foreach ($lineas as $lineaExistente) {
                        $lineaExistente->eliminar();
                    }

                    foreach ($objetosLineas as $linea) {
                        $linea->egreso_id = $egreso->id;
                        $linea->guardar();
                    }

                    Egreso::commit();

                    Egreso::setAlerta('exito', 'Egreso actualizado correctamente');
                    $alertas = Egreso::getAlertas();
                } catch (\Exception $e) {

                    Egreso::rollback();
                    Egreso::setAlerta('error', 'Error interno al actualizar');
                    $alertas = Egreso::getAlertas();
                }
            }
        }

        $router->render('admin/contabilidad/egresos/editar', [
            'titulo' => 'Editar Egreso',
            'egreso' => $egreso,
            'lineas' => $lineas,
            'terceros' => $terceros,
            'cuentasBancarias' => $cuentasBancarias,
            'cuentas' => $cuentas,
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
        }

        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location:/admin/contabilidad/egresos');
            return;
        }

        $egreso = Egreso::find($id);
        $egreso->consecutivo = $egreso->getConsecutivoFormateado();

        if (!$egreso) {
            header('Location:/admin/contabilidad/egresos');
            return;
        }

        $lineas = EgresoCuenta::whereArray([
            'egreso_id' => $egreso->id
        ]);

        $tercero = Tercero::find($egreso->tercero_id);
        $cuentaBancaria = CuentaBancaria::find($egreso->cuenta_bancaria_id);

        // 🔥 RESPONSABLE
        $responsable = \Model\Usuario::find($egreso->user_id);

        $router->render('admin/contabilidad/egresos/ver', [
            'titulo' => $egreso->consecutivo,
            'egreso' => $egreso,
            'lineas' => $lineas,
            'tercero' => $tercero,
            'cuentaBancaria' => $cuentaBancaria,
            'responsable' => $responsable
        ]);
    }
}
