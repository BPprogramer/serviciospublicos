<?php

namespace Controllers\Contabilidad;

use Model\Contabilidad\Cuenta;

class ApiCuentas
{

    // ===============================
    // LISTADO DATATABLE
    // ===============================
    public static function index()
    {

        $cuentas = Cuenta::all();

        $data = [];
        $i = 0;

        foreach ($cuentas as $cuenta) {

            $i++;

            $acciones = "<div class='table__td--acciones'>";
            $acciones .= "<a class='table__accion table__accion--editar' href='/admin/contabilidad/cuentas/editar?id={$cuenta->id}'><i class='fa-solid fa-pen'></i></a> ";
            $acciones .= "<a class='table__accion table__accion--info' href='/admin/contabilidad/cuentas/ver?id={$cuenta->id}'><i class='fa-solid fa-search'></i></a> ";
            $acciones .= "<button class='table__accion table__accion--eliminar' id='btn_eliminar_cuenta' data-cuenta-id='{$cuenta->id}'><i class='fa-solid fa-trash'></i></button>";
            $acciones .= "</div>";


            $data[] = [
                $i,
                $cuenta->codigo ?? '',
                ucfirst($cuenta->nombre ?? ''),
                ucfirst($cuenta->tipo ?? ''),
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

        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            echo json_encode(['tipo' => 'error', 'mensaje' => 'ID inválido']);
            return;
        }

        $cuenta = Cuenta::find($id);

        if (!$cuenta) {
            echo json_encode(['tipo' => 'error', 'mensaje' => 'Cuenta no encontrada']);
            return;
        }

        $resultado = $cuenta->eliminar();

        if (!$resultado) {
            echo json_encode(['tipo' => 'error', 'mensaje' => 'No se pudo eliminar']);
            return;
        }

        echo json_encode(['tipo' => 'success', 'mensaje' => 'Cuenta eliminada correctamente']);
    }
}
