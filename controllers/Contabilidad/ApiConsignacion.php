<?php

namespace Controllers\Contabilidad;

use Model\Contabilidad\Consignacion;
use Model\Contabilidad\CuentaBancaria;
use Model\Usuario;

class ApiConsignacion
{

    // ===============================
    // LISTADO DATATABLE
    // ===============================
    public static function index()
    {

        $consignaciones = Consignacion::all();

        $data = [];
        $i = 0;

        foreach ($consignaciones as $consignacion) {

            $i++;

            $cuentaBancaria = CuentaBancaria::find($consignacion->cuenta_bancaria_id);
            $usuario = Usuario::find($consignacion->usuario_id);

            // =========================
            // ESTADO
            // =========================
            if ($consignacion->anulado) {
                $estado = "<span style='color:#fff;background:#dc3545;padding:4px 8px;border-radius:4px;font-size:12px;'>ANULADO</span>";
            } else {
                $estado = "<span style='color:#fff;background:#28a745;padding:4px 8px;border-radius:4px;font-size:12px;'>ACTIVO</span>";
            }

            // =========================
            // ACCIONES
            // =========================
            $acciones = "<div class='table__td--acciones'>";

            // VER
            $acciones .= "<a class='table__accion table__accion--info' 
                            href='/admin/contabilidad/consignaciones/ver?id={$consignacion->id}'>
                            <i class='fa-solid fa-search'></i>
                          </a> ";

            if (!$consignacion->anulado) {

                $acciones .= "<a class='table__accion table__accion--editar' 
                                href='/admin/contabilidad/consignaciones/editar?id={$consignacion->id}'>
                                <i class='fa-solid fa-pen'></i>
                              </a> ";

                $acciones .= "<button class='table__accion table__accion--eliminar' 
                                id='btn_anular_consignacion' 
                                data-consignacion-id='{$consignacion->id}'>
                                <i class='fa-solid fa-ban'></i>
                              </button>";
            }

            $acciones .= "</div>";

            // =========================
            // DATA
            // =========================
            $data[] = [
                $i,
                $consignacion->fecha,
                $cuentaBancaria->numero_cuenta ?? '',
                ucfirst($consignacion->tipo),

                ucfirst($consignacion->descripcion),
                $usuario
                    ? $usuario->nombre . ' ' . $usuario->apellido
                    : '',
                $estado,
                number_format($consignacion->monto, 2),
                $acciones
            ];
        }

        header('Content-Type: application/json');
        echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
    }



    // ===============================
    // ANULAR CONSIGNACIÓN
    // ===============================
    public static function anular()
    {

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['id'])) {
            header('Location:/login');
            exit;
        }

        $data = json_decode(file_get_contents("php://input"), true);

        $id = $data['id'] ?? null;
        $razon = trim($data['razon_anulacion'] ?? '');

        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            echo json_encode([
                'tipo' => 'error',
                'mensaje' => 'ID inválido'
            ]);
            return;
        }

        if (!$razon) {
            echo json_encode([
                'tipo' => 'error',
                'mensaje' => 'La razón de anulación es obligatoria'
            ]);
            return;
        }

        $consignacion = Consignacion::find($id);

        if (!$consignacion) {
            echo json_encode([
                'tipo' => 'error',
                'mensaje' => 'Consignación no encontrada'
            ]);
            return;
        }

        if ($consignacion->anulado) {
            echo json_encode([
                'tipo' => 'error',
                'mensaje' => 'La consignación ya está anulada'
            ]);
            return;
        }

        $consignacion->anulado = 1;
        $consignacion->razon_anulacion = $razon;
        $consignacion->usuario_id = $_SESSION['id'];

        $resultado = $consignacion->guardar();

        if (!$resultado) {
            echo json_encode([
                'tipo' => 'error',
                'mensaje' => 'No se pudo anular la consignación'
            ]);
            return;
        }

        echo json_encode([
            'tipo' => 'success',
            'mensaje' => 'Consignación anulada correctamente'
        ]);
    }
}
