<?php

namespace Controllers\Contabilidad;

use Model\Contabilidad\CuentaBancaria;
use Model\Contabilidad\Banco;

class ApiCuentaBancaria
{

    // ===============================
    // LISTADO DATATABLE
    // ===============================
    public static function index()
    {

        $cuentas = CuentaBancaria::all();

        $data = [];
        $i = 0;

        foreach ($cuentas as $cuenta) {

            $i++;

            $banco = Banco::find($cuenta->banco_id);

            $acciones = "<div class='table__td--acciones'>";
            $acciones .= "<a class='table__accion table__accion--editar' href='/admin/contabilidad/cuentas-bancarias/editar?id={$cuenta->id}'><i class='fa-solid fa-pen'></i></a> ";
            $acciones .= "<a class='table__accion table__accion--info' href='/admin/contabilidad/cuentas-bancarias/ver?id={$cuenta->id}'><i class='fa-solid fa-search'></i></a> ";
            $acciones .= "<button class='table__accion table__accion--eliminar' id='btn_eliminar_cuenta-bancaria' data-cuenta-id='{$cuenta->id}'><i class='fa-solid fa-trash'></i></button>";
            $acciones .= "</div>";

            $data[] = [
                $i,
                ucfirst($banco->nombre ?? ''),
                $cuenta->numero_cuenta ?? '',
                $cuenta->nombre ?? '',
                number_format($cuenta->saldo_inicial ?? 0, 2),
                $acciones
            ];
        }

        header('Content-Type: application/json');
        echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
    }


    // ===============================
    // ELIMINAR
    // ===============================
    public static function eliminar()
    {

        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            echo json_encode(['tipo' => 'error', 'mensaje' => 'ID inválido']);
            return;
        }

        $cuenta = CuentaBancaria::find($id);

        if (!$cuenta) {
            echo json_encode(['tipo' => 'error', 'mensaje' => 'Cuenta bancaria no encontrada']);
            return;
        }

        $resultado = $cuenta->eliminar();

        if (!$resultado) {
            echo json_encode(['tipo' => 'error', 'mensaje' => 'No se pudo eliminar']);
            return;
        }

        echo json_encode(['tipo' => 'success', 'mensaje' => 'Cuenta bancaria eliminada correctamente']);
    }
}
