<?php

namespace Controllers\Contabilidad;

use Model\Contabilidad\EgresoSimple;
use Model\Contabilidad\CuentaBancaria;
use Model\Usuario;

class ApiEgresosSimples
{

    // ===============================
    // LISTADO DATATABLE
    // ===============================
    public static function index()
    {

        $egresos = EgresoSimple::all();

        $data = [];
        $i = 0;

        foreach ($egresos as $egreso) {

            $i++;

            $cuentaBancaria = CuentaBancaria::find($egreso->cuenta_bancaria_id);
            $usuario = Usuario::find($egreso->user_id);

            // =========================
            // ESTADO
            // =========================
            if ($egreso->anulado) {
                $estado = "<span style='color:#fff;background:#dc3545;padding:4px 8px;border-radius:4px;font-size:12px;'>ANULADO</span>";
            } else {
                $estado = "<span style='color:#fff;background:#28a745;padding:4px 8px;border-radius:4px;font-size:12px;'>ACTIVO</span>";
            }

            // =========================
            // ACCIONES
            // =========================
            $acciones = "<div class='table__td--acciones'>";

            // VER siempre visible
            $acciones .= "<a class='table__accion table__accion--info' 
                            href='/admin/contabilidad/egresos-simples/ver?id={$egreso->id}'>
                            <i class='fa-solid fa-search'></i>
                          </a> ";

            // SOLO SI NO ESTÁ ANULADO
            if (!$egreso->anulado) {

                $acciones .= "<a class='table__accion table__accion--editar' 
                                href='/admin/contabilidad/egresos-simples/editar?id={$egreso->id}'>
                                <i class='fa-solid fa-pen'></i>
                              </a> ";

                $acciones .= "<button class='table__accion table__accion--eliminar' 
                                id='btn_anular_egreso_simple' 
                                data-egreso-id='{$egreso->id}'>
                                <i class='fa-solid fa-ban'></i>
                              </button>";
            }

            $acciones .= "</div>";

            // =========================
            // DATA
            // =========================
            $data[] = [
                $i,
                $egreso->fecha,
                $cuentaBancaria->numero_cuenta ?? '',
                number_format($egreso->monto, 2),
                ucfirst($egreso->descripcion),
                $usuario
                    ? $usuario->nombre . ' ' . $usuario->apellido
                    : '',
                $estado,
                $acciones
            ];
        }

        header('Content-Type: application/json');
        echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
    }




    // ===============================
    // ELIMINAR
    // ===============================
    // ===============================
    // ANULAR EGRESO SIMPLE
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

        // 🔥 CAMBIO AQUÍ → EgresoSimple
        $egreso = EgresoSimple::find($id);

        if (!$egreso) {
            echo json_encode([
                'tipo' => 'error',
                'mensaje' => 'Egreso simple no encontrado'
            ]);
            return;
        }

        if ($egreso->anulado) {
            echo json_encode([
                'tipo' => 'error',
                'mensaje' => 'El egreso ya está anulado'
            ]);
            return;
        }

        $egreso->anulado = 1;
        $egreso->razon_anulacion = $razon;
        $egreso->user_id = $_SESSION['id'];
        $resultado = $egreso->guardar();

        if (!$resultado) {
            echo json_encode([
                'tipo' => 'error',
                'mensaje' => 'No se pudo anular el egreso'
            ]);
            return;
        }

        echo json_encode([
            'tipo' => 'success',
            'mensaje' => 'Egreso simple anulado correctamente'
        ]);
    }
}
