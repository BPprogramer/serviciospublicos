<?php

namespace Controllers\Contabilidad;

use Model\Contabilidad\Egreso;
use Model\Contabilidad\EgresoCuenta;
use Model\Contabilidad\Tercero;
use Model\Contabilidad\CuentaBancaria;
use Model\Usuario;
use TCPDF;

class ApiEgresos
{

    // ===============================
    // LISTADO DATATABLE
    // ===============================
    public static function index()
    {
        $egresos = Egreso::all();
        $data = [];
        $i = 0;

        foreach ($egresos as $egreso) {

            $i++;

            $tercero = Tercero::find($egreso->tercero_id);
            $cuentaBancaria = CuentaBancaria::find($egreso->cuenta_bancaria_id);

            // 🔹 FORMATEAR CONSECUTIVO (AÑO + 4 DIGITOS)
            $anio = date('Y', strtotime($egreso->fecha));
            $consecutivoFormateado = $anio . str_pad($egreso->consecutivo, 4, '0', STR_PAD_LEFT);

            // 🔹 OBTENER LINEAS
            $lineas = EgresoCuenta::whereArray([
                'egreso_id' => $egreso->id
            ]);

            $totalDebito = 0;
            $totalCredito = 0;

            foreach ($lineas as $linea) {
                $totalDebito += (float) $linea->debito;
                $totalCredito += (float) $linea->credito;
            }

            $totalDebito = number_format($totalDebito, 2);
            $totalCredito = number_format($totalCredito, 2);

            // 🔹 ESTADO CON COLOR
            if ($egreso->anulado) {
                $estado = "<span style='color:#fff;background:#dc3545;padding:4px 8px;border-radius:4px;'>Anulado</span>";
            } else {
                $estado = "<span style='color:#fff;background:#28a745;padding:4px 8px;border-radius:4px;'>Activo</span>";
            }

            // 🔹 ACCIONES
            $acciones = "<div class='table__td--acciones'>";

            // Ver siempre permitido
            $acciones .= "<a class='table__accion table__accion--info' 
                href='/admin/contabilidad/egresos/ver?id={$egreso->id}'>
                <i class='fa-solid fa-search'></i>
              </a> ";

            // Solo si NO está anulado
            if (!$egreso->anulado) {

                $acciones .= "<a class='table__accion table__accion--editar' 
                    href='/admin/contabilidad/egresos/editar?id={$egreso->id}'>
                    <i class='fa-solid fa-pen'></i>
                  </a> ";

                $acciones .= "<a class='table__accion table__accion--editar' 
                    target='_blank' 
                    href='/api/contabilidad/egresos/imprimir?id={$egreso->id}'>
                    <i class='fa-solid fa-print'></i>
                  </a> ";

                $acciones .= "<button class='table__accion table__accion--eliminar' 
                    id='btn_eliminar_egreso' 
                    data-egreso-id='{$egreso->id}'>
                    <i class='fa-solid fa-ban'></i>
                  </button>";
            }

            $acciones .= "</div>";

            $acciones .= "</div>";

            $data[] = [
                $i,
                $consecutivoFormateado,
                $egreso->fecha,
                ucfirst($tercero->nombre ?? ''),
                $cuentaBancaria->numero_cuenta ?? '',
                $totalDebito,
                $totalCredito,
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
            echo json_encode(['tipo' => 'error', 'mensaje' => 'ID inválido']);
            return;
        }

        if (!$razon) {
            echo json_encode(['tipo' => 'error', 'mensaje' => 'La razón de anulación es obligatoria']);
            return;
        }

        $egreso = Egreso::find($id);

        if (!$egreso) {
            echo json_encode(['tipo' => 'error', 'mensaje' => 'Egreso no encontrado']);
            return;
        }

        if ($egreso->anulado) {
            echo json_encode(['tipo' => 'error', 'mensaje' => 'El egreso ya está anulado']);
            return;
        }

        $egreso->anulado = 1;
        $egreso->razon_anulacion = $razon;
        $egreso->user_id = $_SESSION['id'];

        $resultado = $egreso->guardar();

        if (!$resultado) {
            echo json_encode(['tipo' => 'error', 'mensaje' => 'No se pudo anular el egreso']);
            return;
        }

        echo json_encode([
            'tipo' => 'success',
            'mensaje' => 'Egreso anulado correctamente'
        ]);
    }



    public static function imprimir()
    {
        if (!is_auth()) {
            header('Location:/login');
            return;
        }

        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) die('ID inválido');

        $egreso = Egreso::find($id);
        if (!$egreso) die('Egreso no encontrado');
        if ($egreso->anulado) die('No se puede imprimir un egreso anulado');

        $tercero = Tercero::find($egreso->tercero_id);
        $lineas = EgresoCuenta::whereArray(['egreso_id' => $egreso->id]);
        $usuario = Usuario::find($egreso->user_id);

        // ===============================
        // TOTALES
        // ===============================
        $totalDebito = 0;
        $totalCredito = 0;

        foreach ($lineas as $l) {
            $totalDebito += $l->debito;
            $totalCredito += $l->credito;
        }

        // ===============================
        // CONSECUTIVO
        // ===============================
        $anio = date('Y', strtotime($egreso->fecha));
        $consecutivoFormateado = $anio . str_pad($egreso->consecutivo, 4, '0', STR_PAD_LEFT);

        $valorEnLetras = self::convertirNumero($totalCredito);

        // ===============================
        // PDF
        // ===============================
        $pdf = new TCPDF();
        $pdf->setPrintHeader(false);
        $pdf->AddPage();
        $pdf->SetMargins(15, 15, 15);

        // LOGO
        $pdf->Image('../public/build/img/logo.png', 15, 10, 28);

        // ENCABEZADO
        $pdf->SetY(12);
        $pdf->SetX(50);
        $pdf->SetFont('dejavusans', 'B', 10);
        $pdf->Cell(150, 5, 'ASOCIACION DE USUARIOS ADMINISTRADORA DE SERVICIOS PUBLICOS', 0, 1, 'C');
        $pdf->SetX(50);
        $pdf->Cell(150, 5, 'DE ACUEDUCTO, ALCANTARILLADO Y ASEO DEL CASCO URBANO', 0, 1, 'C');
        $pdf->SetX(50);
        $pdf->Cell(150, 5, 'NIT 900324139-0', 0, 1, 'C');

        $pdf->Ln(12);

        // TITULO
        $pdf->SetFont('dejavusans', 'B', 13);
        $pdf->Cell(0, 6, 'COMPROBANTE DE EGRESO', 0, 1, 'R');

        $pdf->SetTextColor(200, 0, 0);
        $pdf->Cell(0, 6, $consecutivoFormateado, 0, 1, 'R');
        $pdf->SetTextColor(0, 0, 0);

        $pdf->Ln(8);

        // ===============================
        // VALOR GRANDE
        // ===============================
        $pdf->SetFont('dejavusans', 'B', 16);
        $pdf->Cell(0, 8, '$ ' . number_format($totalCredito, 2), 0, 1, 'L');

        $pdf->Ln(5);

        $pdf->SetFont('dejavusans', '', 10);

        // ===============================
        // FORMATO CLÁSICO VERTICAL
        // ===============================

        $pdf->Cell(45, 6, 'FORMA DE PAGO:', 0, 0);
        $pdf->Cell(100, 6, 'TRANSFERENCIA', 0, 1);

        $pdf->Cell(45, 6, 'FECHA:', 0, 0);
        $pdf->Cell(100, 6, $egreso->fecha, 0, 1);

        $pdf->Ln(4);

        $pdf->Cell(45, 6, 'PAGADO A:', 0, 0);
        $pdf->Cell(100, 6, $tercero->nombre, 0, 1);

        $pdf->Cell(45, 6, 'NIT / DOCUMENTO:', 0, 0);
        $pdf->Cell(100, 6, $tercero->documento, 0, 1);

        $pdf->Ln(4);

        // ===============================
        // SUMA EN LETRAS
        // ===============================
        $pdf->Cell(45, 6, 'LA SUMA DE:', 0, 0);
        $pdf->MultiCell(140, 6, $valorEnLetras, 0, 'L');

        $pdf->Ln(3);

        $pdf->Cell(45, 6, 'POR CONCEPTO DE:', 0, 0);
        $pdf->MultiCell(140, 6, strtoupper($egreso->detalle), 0, 'L');

        $pdf->Ln(8);

        // ===============================
        // MOVIMIENTO CONTABLE
        // ===============================
        $pdf->SetFont('dejavusans', 'B', 11);
        $pdf->Cell(0, 6, 'MOVIMIENTO CONTABLE', 0, 1);

        $pdf->SetFont('dejavusans', 'B', 9);
        $pdf->Cell(35, 7, 'Codigo', 1);
        $pdf->Cell(75, 7, 'Cuenta', 1);
        $pdf->Cell(40, 7, 'Debito', 1);
        $pdf->Cell(40, 7, 'Credito', 1);
        $pdf->Ln();

        $pdf->SetFont('dejavusans', '', 9);

        foreach ($lineas as $l) {

            $cuenta = \Model\Contabilidad\Cuenta::find($l->cuenta_id);

            $pdf->Cell(35, 6, $cuenta->codigo ?? '', 1);
            $pdf->Cell(75, 6, $cuenta->nombre ?? '', 1);
            $pdf->Cell(40, 6, number_format($l->debito, 2), 1, 0, 'R');
            $pdf->Cell(40, 6, number_format($l->credito, 2), 1, 0, 'R');
            $pdf->Ln();
        }

        $pdf->SetFont('dejavusans', 'B', 9);
        $pdf->Cell(110, 7, 'SUMAS IGUALES', 1);
        $pdf->Cell(40, 7, number_format($totalDebito, 2), 1, 0, 'R');
        $pdf->Cell(40, 7, number_format($totalCredito, 2), 1, 0, 'R');

        $pdf->Ln(20);

        // ===============================
        // FIRMAS
        // ===============================
        $pdf->Cell(60, 6, '________________________', 0, 0, 'C');
        $pdf->Cell(60, 6, '________________________', 0, 0, 'C');
        $pdf->Cell(60, 6, '________________________', 0, 1, 'C');

        $pdf->Cell(60, 6, $tercero->nombre, 0, 0, 'C');
        $pdf->Cell(60, 6, $usuario->nombre . ' ' . $usuario->apellido, 0, 0, 'C');
        $pdf->Cell(60, 6, 'APROBO', 0, 1, 'C');

        $pdf->Output('Comprobante_Egreso.pdf', 'I');
    }



    private static function convertirNumero($numero)
    {
        $numero = floor($numero);

        $letras = strtoupper(self::numeroEnLetras($numero));

        if ($numero % 1000000 == 0) {
            return $letras . " DE PESOS M/CTE";
        }

        return $letras . " PESOS M/CTE";
    }

    private static function numeroEnLetras($numero)
    {
        $unidades = [
            "",
            "UNO",
            "DOS",
            "TRES",
            "CUATRO",
            "CINCO",
            "SEIS",
            "SIETE",
            "OCHO",
            "NUEVE",
            "DIEZ",
            "ONCE",
            "DOCE",
            "TRECE",
            "CATORCE",
            "QUINCE",
            "DIECISEIS",
            "DIECISIETE",
            "DIECIOCHO",
            "DIECINUEVE",
            "VEINTE"
        ];

        $decenas = ["", "", "VEINTE", "TREINTA", "CUARENTA", "CINCUENTA", "SESENTA", "SETENTA", "OCHENTA", "NOVENTA"];

        $centenas = [
            "",
            "CIENTO",
            "DOSCIENTOS",
            "TRESCIENTOS",
            "CUATROCIENTOS",
            "QUINIENTOS",
            "SEISCIENTOS",
            "SETECIENTOS",
            "OCHOCIENTOS",
            "NOVECIENTOS"
        ];

        if ($numero == 100) return "CIEN";

        if ($numero < 21) return $unidades[$numero];

        if ($numero < 30) return "VEINTI" . strtolower($unidades[$numero - 20]);

        if ($numero < 100) {
            $d = intval($numero / 10);
            $r = $numero % 10;
            return ($r == 0) ? $decenas[$d] : $decenas[$d] . " Y " . $unidades[$r];
        }

        if ($numero < 1000) {
            $c = intval($numero / 100);
            $r = $numero % 100;
            return ($r == 0) ? $centenas[$c] : $centenas[$c] . " " . self::numeroEnLetras($r);
        }

        if ($numero < 1000000) {
            $m = intval($numero / 1000);
            $r = $numero % 1000;
            $texto = ($m == 1) ? "MIL" : self::numeroEnLetras($m) . " MIL";
            return ($r == 0) ? $texto : $texto . " " . self::numeroEnLetras($r);
        }

        if ($numero < 1000000000) {
            $millones = intval($numero / 1000000);
            $r = $numero % 1000000;

            $texto = ($millones == 1)
                ? "UN MILLON"
                : self::numeroEnLetras($millones) . " MILLONES";

            return ($r == 0)
                ? $texto
                : $texto . " " . self::numeroEnLetras($r);
        }

        return "";
    }
}
