<?php

namespace Controllers\Contabilidad;

use Model\Contabilidad\CuentaBancaria;
use Model\Contabilidad\Banco;
use Model\Contabilidad\Egreso;
use Model\Contabilidad\EgresoSimple;
use Model\Contabilidad\Consignacion;
use MVC\Router;

class CuentaBancariaController
{
    public static function index(Router $router)
    {
        $router->render('admin/contabilidad/cuentas_bancarias/index', [
            'titulo' => 'Cuentas Bancarias'
        ]);
    }

    public static function crear(Router $router)
    {
        $alertas = [];
        $cuenta = new CuentaBancaria();

        // Obtener bancos para select
        $bancos = Banco::all();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $cuenta->sincronizar($_POST);
            $alertas = $cuenta->validar();

            if (empty($alertas)) {

                $resultado = $cuenta->guardar();

                if ($resultado) {
                    $cuenta = new CuentaBancaria([]);
                    CuentaBancaria::setAlerta('exito', 'Cuenta bancaria creada correctamente');
                    $alertas = CuentaBancaria::getAlertas();
                }
            }
        }

        $router->render('admin/contabilidad/cuentas_bancarias/crear', [
            'titulo' => 'Crear Cuenta Bancaria',
            'cuenta' => $cuenta,
            'bancos' => $bancos,
            'alertas' => $alertas
        ]);
    }

    public static function editar(Router $router)
    {
        $alertas = [];

        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location:/admin/contabilidad/cuentas_bancarias');
            return;
        }

        $cuenta = CuentaBancaria::find($id);

        if (!$cuenta) {
            header('Location:/admin/contabilidad/cuentas_bancarias');
            return;
        }

        $bancos = Banco::all();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $cuenta->sincronizar($_POST);
            $alertas = $cuenta->validar();

            if (empty($alertas)) {

                $resultado = $cuenta->guardar();

                if ($resultado) {
                    CuentaBancaria::setAlerta('exito', 'Cuenta bancaria actualizada correctamente');
                    $alertas = CuentaBancaria::getAlertas();
                }
            }
        }

        $router->render('admin/contabilidad/cuentas_bancarias/editar', [
            'titulo' => 'Editar Cuenta Bancaria',
            'cuenta' => $cuenta,
            'bancos' => $bancos,
            'alertas' => $alertas
        ]);
    }

    public static function ver(Router $router)
    {
        if (!is_auth()) {
            header('Location:/login');
        }

        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location:/admin/contabilidad/cuentas_bancarias');
            return;
        }

        $cuenta = CuentaBancaria::find($id);

        if (!$cuenta) {
            header('Location:/admin/contabilidad/cuentas_bancarias');
            return;
        }


        $banco = Banco::find($cuenta->banco_id);

        // =========================
        // CALCULAR SALDO ACTUAL
        // =========================

        $saldoInicial = (float) $cuenta->saldo_inicial;

        $totalConsignaciones = Consignacion::sumWhere([
            'cuenta_bancaria_id' => $cuenta->id,
            'anulado' => 0
        ], 'monto');

        $totalEgresosSimples = EgresoSimple::sumWhere([
            'cuenta_bancaria_id' => $cuenta->id,
            'anulado' => 0
        ], 'monto');

        $totalEgresos = Egreso::totalCreditoCuentaBancaria($cuenta->id);

       
        $saldoActual = $saldoInicial
            + (float)$totalConsignaciones
            - (float)$totalEgresosSimples
            - (float)$totalEgresos;



        $router->render('admin/contabilidad/cuentas_bancarias/ver', [
            'titulo'  => $cuenta->numero_cuenta,
            'cuenta'  => $cuenta,
            'banco'   => $banco,
            'saldoActual' => $saldoActual,
            'totalConsignaciones' => $totalConsignaciones,
            'totalEgresosSimples' => $totalEgresosSimples,
            'totalEgresos' => $totalEgresos
        ]);
    }
}
