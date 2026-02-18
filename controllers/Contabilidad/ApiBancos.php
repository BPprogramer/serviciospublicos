<?php

namespace Controllers\Contabilidad;

use Model\Contabilidad\Banco;

class ApiBancos
{

    // ===============================
    // LISTADO DATATABLE
    // ===============================
    public static function index()
    {

        $bancos = Banco::all();

        $data = [];
        $i = 0;

        foreach ($bancos as $banco) {

            $i++;

            $acciones = "<div class='table__td--acciones'>";
            $acciones .= "<a class='table__accion table__accion--editar' href='/admin/contabilidad/bancos/editar?id={$banco->id}'><i class='fa-solid fa-pen'></i></a> ";
            $acciones .= "<a class='table__accion table__accion--info' href='/admin/contabilidad/bancos/ver?id={$banco->id}'><i class='fa-solid fa-search'></i></a> ";
            $acciones .= "<button class='table__accion table__accion--eliminar' id='btn_eliminar_banco' data-banco-id='{$banco->id}'><i class='fa-solid fa-trash'></i></button>";
            $acciones .= "</div>";

            $data[] = [
                $i,
                ucfirst($banco->nombre ?? ''),
                $banco->codigo ?? '',
                $banco->nit ?? '',
                $banco->dv ?? '',
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

        $banco = Banco::find($id);

        if (!$banco) {
            echo json_encode(['tipo' => 'error', 'mensaje' => 'Banco no encontrado']);
            return;
        }

        $resultado = $banco->eliminar();

        if (!$resultado) {
            echo json_encode(['tipo' => 'error', 'mensaje' => 'No se pudo eliminar']);
            return;
        }

        echo json_encode(['tipo' => 'success', 'mensaje' => 'Banco eliminado correctamente']);
    }
}
