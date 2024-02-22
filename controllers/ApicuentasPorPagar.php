<?php

namespace Controllers;

use Model\Factura;
use Model\Registrado;
use TCPDF;

class ApiCuentasPorPagar
{
    public static function cuentasPorPagar()
    {

   
        if(!is_auth()){
            header('Location:/login');
        }
        $fecha = date('Y-m', strtotime('-1 month'));

        $facturas = Factura::fechas($fecha);

   
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->AddPage('p', 'A3');
        $pdf->SetMargins(17, 10, 17);
        // # Logo de la empresa formato png #
        $imagen_posicion = 10;
        $pdf->Image('../public/build/img/logo.png', 17, $imagen_posicion, 52, 38, 'PNG');
        // # Encabezado y datos de la empresa #
        $pdf->Cell(87);
        $pdf->SetFont('dejavusans', 'B', 11);
        $pdf->SetTextColor(11, 78, 187);
        $pdf->Cell(150, 10, 'Asociación de Usuarios Administradores de Acueducto, Alcantarillado y Aseo', 0, 0, 'C');

        $pdf->Ln(5);
        $pdf->Cell(87);
        $pdf->Cell(150, 10, 'del Casco Urbano de El Tablón de Gómez', 0, 0, 'C');
        $pdf->Ln(6);
        $pdf->Cell(87);
        $pdf->SetFont('dejavusans', 'B', 14);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(150, 10, 'ASUAAASTAB', 0, 0, 'C');
        $pdf->Ln(6);
        $pdf->Cell(87);
        $pdf->SetFont('dejavusans', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(150, 10, 'Nit: 900324139-0', 0, 0, 'C');
        $pdf->Ln(6);
        $pdf->Cell(87);
        $pdf->SetFont('dejavusans', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(150, 10, '3216549877', 0, 0, 'C');
        $pdf->Ln(6);
        $pdf->Cell(87);
        $pdf->SetFont('dejavusans', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(150, 10, 'El Tablón de Gómez', 0, 0, 'C');

        $pdf->Ln(15);
        $pdf->Cell(50);
        $pdf->SetFont('dejavusans', 'B', 15);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(150, 10, 'Cuentas por pagar', 0, 0, 'C');
        $pdf->Ln(15);

        $pdf->SetLineWidth(0.1);
        $pdf->SetFont('dejavusans','B',10);
        $pdf->SetFillColor(11,78,187);
        $pdf->SetDrawColor(11,78,187);
        $pdf->SetTextColor(255,255,255);
        
        $pdf->SetMargins(17, 10, 17);

        $pdf->Cell(90,7,"Cliente",1,0,'C',true);
        $pdf->Cell(60,7,"Dirección",1,0,'C',true);
        $pdf->Cell(50,7,"estrato",1,0,'C',true);
        // $pdf->Cell(1);
        $pdf->Cell(20,7,"Facturas",1,0,'C',true);
        // $pdf->Cell(1);
        $pdf->Cell(27,7,"Deuda",1,0,'L',true);

        $pdf->Ln(8);
        $pdf->SetLineWidth(0.1);
        $pdf->SetFont('dejavusans','B',10);
        $pdf->SetDrawColor(11,78,187);
        $pdf->setFillColor(255,255,255);
        $pdf->SetTextColor(0,0,0);
        $total = 0;
        foreach ($facturas as $factura) {
            if($factura->saldo_anterior>0 && $factura->pagado==0){
                $total = $total + $factura->saldo_anterior;
                $registrado= Registrado::find($factura->registrado_id);
                $pdf->Cell(90,7,$registrado->nombre." ".$registrado->apellido,1,0,'L',true);
                $pdf->Cell(60,7,$registrado->direccion,1,0,'L',true);
                $pdf->Cell(50,7,$factura->estrato,1,0,'L',true);
                // $pdf->Cell(1);
                $pdf->Cell(20,7,$registrado->facturas,1,0,'C',true);
                // $pdf->Cell(1);
                $pdf->Cell(27,7,number_format($factura->saldo_anterior),1,0,'L',true);
                $pdf->Ln(7);
            }
           
        }
      
        $pdf->SetLineWidth(0.1);
        $pdf->SetFont('dejavusans','B',10);
        $pdf->SetFillColor(11,78,187);
        $pdf->SetDrawColor(11,78,187);
        $pdf->SetTextColor(255,255,255);

        $pdf->Ln(15);
        $pdf->Cell(133);
        $pdf->Cell(76,7,"Total por pagar",1,0,'C',true);
        $pdf->Cell(1);
        $pdf->Cell(27,7,number_format($total),1,0,'L',true);
        
   

     

   




        $pdf->Output('example_027.pdf', 'I');
    }
}
