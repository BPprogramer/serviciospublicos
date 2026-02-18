<?php

namespace Controllers\Contabilidad;

use Model\Contabilidad\Tercero;

class ApiTerceros
{

    // ===============================
    // LISTADO DATATABLE
    // ===============================
    public static function index()
    {


        $terceros = Tercero::all();


        $data = [];
        $i = 0;

        foreach ($terceros as $tercero) {


            $i++;

            $acciones = "<div class='table__td--acciones'>";
            $acciones .= "<a class='table__accion table__accion--editar' href='/admin/contabilidad/terceros/editar?id={$tercero->id}'><i class='fa-solid fa-pen'></i></a> ";
            $acciones .= "<button class='table__accion table__accion--eliminar' id='btn_eliminar_tercero' data-tercero-id='{$tercero->id}'><i class='fa-solid fa-trash'></i></button> ";
            $acciones .= "</div>";

            $data[] = [
                $i,
                ucfirst($tercero->tipo_persona ?? ''),
                $tercero->nombre ?? '',
                $tercero->documento ?? '',
                $tercero->telefono ?? '',
                $tercero->ciudad ?? '',
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

        $tercero = Tercero::find($id);

        if (!$tercero) {
            echo json_encode(['tipo' => 'error', 'mensaje' => 'Tercero no encontrado']);
            return;
        }

        $resultado = $tercero->eliminar();

        if (!$resultado) {
            echo json_encode(['tipo' => 'error', 'mensaje' => 'No se pudo eliminar']);
            return;
        }

        echo json_encode(['tipo' => 'success', 'mensaje' => 'Tercero eliminado correctamente']);
    }
}
