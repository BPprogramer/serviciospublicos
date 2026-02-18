<?php

namespace Controllers\Contabilidad;

use Model\Contabilidad\Consignacion;
use Model\Contabilidad\EgresoSimple;
use Model\Contabilidad\EgresoCuenta;
use Model\ActiveRecord;

class ApiMovimientosCuenta
{

    // =====================================================
    // CONSIGNACIONES
    // =====================================================
    public static function consignaciones()
    {
        $cuentaId   = $_GET['cuenta_id'] ?? null;
        $fechaInicio = $_GET['fecha_inicio'] ?? null;
        $fechaFin    = $_GET['fecha_fin'] ?? null;

        if (!$cuentaId || !$fechaInicio || !$fechaFin) {
            echo json_encode(['data' => []]);
            return;
        }

        $query = "
            SELECT fecha, tipo, descripcion, monto, anulado
            FROM consignaciones
            WHERE cuenta_bancaria_id = '$cuentaId'
            AND fecha BETWEEN '$fechaInicio' AND '$fechaFin'
            ORDER BY fecha DESC
        ";

        $resultado = Consignacion::consultarSQL($query);

        $data = [];

        foreach ($resultado as $row) {

            $estado = $row->anulado
                ? "<span style='color:#fff;background:#dc3545;padding:4px 8px;border-radius:4px;'>Anulado</span>"
                : "<span style='color:#fff;background:#28a745;padding:4px 8px;border-radius:4px;'>Activo</span>";

            $data[] = [
                $row->fecha,
                ucfirst($row->tipo),
                $row->descripcion,
                $estado,
                number_format($row->monto, 2)
            ];
        }

        echo json_encode(['data' => $data]);
    }



    // =====================================================
    // EGRESOS SIMPLES
    // =====================================================
    public static function egresosSimples()
    {
        $cuentaId   = $_GET['cuenta_id'] ?? null;
        $fechaInicio = $_GET['fecha_inicio'] ?? null;
        $fechaFin    = $_GET['fecha_fin'] ?? null;

        if (!$cuentaId || !$fechaInicio || !$fechaFin) {
            echo json_encode(['data' => []]);
            return;
        }

        $query = "
            SELECT fecha, descripcion, monto, anulado
            FROM egresos_simples
            WHERE cuenta_bancaria_id = '$cuentaId'
            AND fecha BETWEEN '$fechaInicio' AND '$fechaFin'
            ORDER BY fecha DESC
        ";

        $resultado = EgresoSimple::consultarSQL($query);

        $data = [];

        foreach ($resultado as $row) {

            $estado = $row->anulado
                ? "<span style='color:#fff;background:#dc3545;padding:4px 8px;border-radius:4px;'>Anulado</span>"
                : "<span style='color:#fff;background:#28a745;padding:4px 8px;border-radius:4px;'>Activo</span>";

            $data[] = [
                $row->fecha,
                $row->descripcion,
                $estado,
                number_format($row->monto, 2)
            ];
        }

        echo json_encode(['data' => $data]);
    }



    // =====================================================
    // EGRESOS CONTABLES
    // =====================================================
    public static function egresos()
    {
        $cuentaId   = $_GET['cuenta_id'] ?? null;
        $fechaInicio = $_GET['fecha_inicio'] ?? null;
        $fechaFin    = $_GET['fecha_fin'] ?? null;

        if (!$cuentaId || !$fechaInicio || !$fechaFin) {
            echo json_encode(['data' => []]);
            return;
        }

        // 🔥 Traemos también anulados
        $egresos = ActiveRecord::queryRaw("
            SELECT *
            FROM egresos
            WHERE cuenta_bancaria_id = $cuentaId
            AND fecha BETWEEN '$fechaInicio' AND '$fechaFin'
            ORDER BY fecha DESC
        ");

        $data = [];

        foreach ($egresos as $egreso) {

            // 🔹 Obtener líneas contables
            $lineas = EgresoCuenta::whereArray([
                'egreso_id' => $egreso['id']
            ]);

            $totalDebito  = 0;
            $totalCredito = 0;

            foreach ($lineas as $linea) {
                $totalDebito  += (float) $linea->debito;
                $totalCredito += (float) $linea->credito;
            }

            $anio = date('Y', strtotime($egreso['fecha']));
            $consecutivoFormateado = $anio . str_pad($egreso['consecutivo'], 4, '0', STR_PAD_LEFT);

            $estado = $egreso['anulado']
                ? "<span style='color:#fff;background:#dc3545;padding:4px 8px;border-radius:4px;'>Anulado</span>"
                : "<span style='color:#fff;background:#28a745;padding:4px 8px;border-radius:4px;'>Activo</span>";

            $data[] = [
                $egreso['fecha'],
                $consecutivoFormateado,
       
                $egreso['detalle'] ?? '',
                $estado,
                number_format($totalDebito, 2),
                number_format($totalCredito, 2)
            ];
        }

        header('Content-Type: application/json');
        echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
    }
}
