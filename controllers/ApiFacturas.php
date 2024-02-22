<?php 

    namespace Controllers;
    

    use DateTime;
    use FPDF;
    use TCPDF;
    use Model\Estrato;
    use Model\Factura;
    use Model\Registrado;
    use Model\GeneraAuto;

    // require __DIR__.'/../vendor/setasign/fpdf/fpdf.php';


/* echo "<pre>";
var_dump($columna);
echo "</pre>"; */

    class ApiFacturas{

        public static function facturas(){

      
            if(!is_auth()){
                header('Location:/login');
            }

            $idEstrato = null;
            $estratoNombre = '';
            if(isset($_GET['estratos-key'])){
                $idEstrato = base64_decode($_GET['estratos-key']);
                $idestrato = filter_var($idEstrato, FILTER_VALIDATE_INT);
                if($idestrato){
                    $estrato = Estrato::find($idEstrato);
                    $estratoNombre = $estrato->estrato;
                   
                }else{
                    $idestrato = null;
                }
            }

  
        
            
            $fecha = date('Y-m', strtotime('-1 month'));
            $facturas = [];
            $facturas__all = Factura::fechas($fecha);
            if($idEstrato){
                foreach($facturas__all as $factura){
                    if(trim($estratoNombre)!=trim($factura->estrato)) continue;
                    $facturas[] = $factura;
                }
               
            }else{
                $facturas = $facturas__all;
            }
            
        
       

            
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->setPrintHeader(false);
            foreach($facturas as $key=>$factura){
             
              
                $factura->fecha_emision =date('Y-m',strtotime($factura->fecha_emision));
            
        
                $registrado = Registrado::find($factura->registrado_id);
        
                $facturas_vencidas = $registrado->facturas;
                if($registrado->facturas>0){
                    $facturas_vencidas = $registrado->facturas-1;
                }
        
                mb_internal_encoding('UTF-8');
                // $factura->formatearDatosNumber();
                $imagen_posicion = 215;
                $total_pagar_posicion = 369;
                if($key%2 == 0){
                    $imagen_posicion = 10;
                    $total_pagar_posicion = 164;
                     $pdf->AddPage('p', 'A3');
                }
                
           

                
                // $pdf = new FPDF('P','mm','Letter');

                // $pdf->AddPage();
                $pdf->SetMargins(17,10, 17);
                // # Logo de la empresa formato png #

                $pdf->Image('../public/build/img/logo.png',17,$imagen_posicion,52,38,'PNG');
                // Relleno rojo (RGB)
                
            
                $pdf->Ln(0);
            
            
                

                // # Encabezado y datos de la empresa #
                $pdf->Cell(87);
                $pdf->SetFont('dejavusans','B',11);
                $pdf->SetTextColor(11,78,187);
                $pdf->Cell(150,10,'Asociación de Usuarios Administradores de Acueducto, Alcantarillado y Aseo',0,0,'C');

                $pdf->Ln(5);
                $pdf->Cell(87);
                $pdf->Cell(150,10,'del Casco Urbano de El Tablón de Gómez',0,0,'C');
                $pdf->Ln(6);
                $pdf->Cell(87);
                $pdf->SetFont('dejavusans','B',14);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(150,10,'ASUAAASTAB',0,0,'C');
                $pdf->Ln(6);
                $pdf->Cell(87);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(150,10,'Nit: 900324139-0',0,0,'C');
                $pdf->Ln(6);
                $pdf->Cell(87);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(150,10,'3216549877',0,0,'C');
                $pdf->Ln(6);
                $pdf->Cell(87);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(150,10,'El Tablón de Gómez',0,0,'C');

                $pdf->Ln(10);

                    //informacion cCliente
                $pdf->SetLineWidth(0.1);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->SetFillColor(11,78,187);
                $pdf->SetDrawColor(11,78,187);
                $pdf->SetTextColor(255,255,255);
                $pdf->Cell(130,7,"Información del Cliente",1,0,'C',true);
                $pdf->Cell(3);
                $pdf->Cell(76,7,"Información de la Factura",1,0,'C',true);
                $pdf->Cell(1);
                $pdf->Cell(27,7,"Factura No",1,0,'L',true);
                
                $pdf->SetFillColor(255, 255, 255);
                $pdf->SetDrawColor(11,78,187);
                $pdf->SetTextColor(255,0,0);
                $pdf->Cell(27,7,$factura->numero_factura,1,0,'C',true);
                

                $pdf->Cell(3);
            

                $pdf->Ln(7);
                $pdf->SetFillColor(255,255,255);
                $pdf->SetTextColor(0,0,0);

                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(30,6,"  Usuario: ", 'L',0,0,'L',true);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(100,6,"$registrado->nombre $registrado->apellido", 'R',0,0,'L',true);
                $pdf->Cell(3);

       

                $pdf->SetDrawColor(11,78,187);
                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(76,6,"  Mes Factuardo",1,0,'L',true);
                $pdf->Cell(1);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(54,6,date('Y-m',strtotime($factura->mes_facturado)).'  ',1,0,'R',true);

                
                $pdf->Ln(6);

                            
                $year = date('Y',strtotime($factura->fecha_emision)); // Año con 4 dígitos
                $month = date('m',strtotime($factura->fecha_emision)); // Mes con 2 dígitos

                // Calcular el último día del mes
                $lastDay = date('t', strtotime("$year-$month-01"));

                // Crear la fecha completa con el último día del mes
                $lastDayOfMonth = date("$year-$month-$lastDay");


                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(30,6,"  Cedula/Nit: ", 'L',0,0,'L',true);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(100,6,"$registrado->cedula_nit", 'R',0,0,'L',true);
                $pdf->SetDrawColor(11,78,187);
                $pdf->Cell(3);
                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(76,6,"  Fecha de Pago Oportuno",1,0,'L',true);
                $pdf->Cell(1);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(54,6,$lastDayOfMonth.'  ',1,0,'R',true);

            

                $pdf->Ln(6);
                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(30,6,"  Dirección:", 'L',0,0,'L',true);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(100,6,"$registrado->direccion", 'R',0,0,'L',true);
                $pdf->Cell(3);
                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(76,6,"  Facturas Vencidas",1,0,'L',true);
                $pdf->Cell(1);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(54,6,$facturas_vencidas. '  ',1,0,'R',true);
                $pdf->Ln(6);
                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(30,6,"  Barrio: ", 'L',0,0,'L',true);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(100,6,"$registrado->barrio", 'R',0,0,'L',true);
                $pdf->Cell(3);
                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(76,6,"  Saldo Anterior",1,0,'L',true);
                $pdf->Cell(1);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(54,6,'$'.number_format($factura->saldo_anterior). '  ',1,0,'R',true);
                
                $pdf->SetFont('dejavusans','',10);
                $pdf->Ln(6);
                $pdf->Cell(30,6,"  Código: ", 'L',0,0,'L',true);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(100,6,"$registrado->codigo_ubicacion", 'R',0,0,'L',true);
                $pdf->Cell(3);
                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(76,6,"  Pago Mensual",1,0,'L',true);
                $pdf->Cell(1);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(54,6,'$'.number_format($factura->copago-$factura->ajuste). '  ',1,0,'R',true);
                $pdf->Ln(6);
                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(30,6,"  Estrato: ", 'LB',0,0,'L',true);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(100,6,trim($factura->estrato), 'RB',0,0,'L',true);
                $pdf->Cell(3);
                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(76,6,"  Total Pagar",1,0,'L',true);
                $pdf->Cell(1);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(54,6,'$'.number_format($factura->copago-$factura->ajuste+$factura->saldo_anterior). '  ',1,0,'R',true);

                /* infomraicon acueducto aseo, alcantarillado y pago fina */
                $pdf->Ln(8);
                $pdf->SetLineWidth(0.1);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->SetFillColor(11,78,187);
                $pdf->SetDrawColor(11,78,187);
                $pdf->SetTextColor(255,255,255);
                $pdf->Cell(69,6,"Liquidación Acueducto",1,0,'C',true);
                $pdf->Cell(1);
                $pdf->Cell(73,6,"Liquidación Alcantarillado",1,0,'C',true);
                $pdf->Cell(1);
                $pdf->Cell(65,6,"Liquidación Aseo",1,0,'C',true);
                $pdf->Cell(1);
                $pdf->Cell(54,6,"Ajuste",1,0,'C',true);

                $pdf->Ln(6);
                $pdf->SetFillColor(255,255,255);
                $pdf->SetTextColor(0,0,0);

                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(24,6,"  Cargo Fijo: ", 'L',0,0,'L',true);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(45,6,"$".number_format($factura->copago_acu). '  ', 'R',0,'R',true);

                $pdf->Cell(1);
                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(25,6,"  Cargo Fijo: ", 'L',0,0,'L',true);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(48,6,"$".number_format($factura->copago_alc). '  ', 'R',0,'R',true);
                $pdf->Cell(1);
                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(25,6,"  Cargo Fijo: ", 'L',0,0,'L',true);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(40,6,"$".number_format($factura->copago_aseo). '  ', 'R',0,'R',true);
                $pdf->Cell(1);
                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(22,6,"  Tarifario: ", 'L',0,0,'L',true);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(32,6,"$".number_format($factura->copago_aseo). '  ', 'R',0,'R',true);

                $pdf->Ln(6);
                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(24,6,"  Subsidiado: ", 'LB',0,0,'L',true);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(45,6,"$".number_format($factura->subsidio_acu). '  ', 'RB',0,'R',true);
                $pdf->Cell(1);
                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(25,6,"  Subsidiado: ", 'L',0,0,'L',true);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(48,6,"$".number_format($factura->subsidio_alc). '  ', 'R',0,'R',true);
                $pdf->Cell(1);
                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(25,6,"  Subsidiado: ", 'L',0,0,'L',true);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(40,6,"$".number_format($factura->subsidio_aseo). '  ', 'R',0,'R',true);
                $pdf->Cell(1);
                $pdf->Cell(54,6,"", 'LR',0,'R',true);
                $pdf->Ln(6);

                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(40,6,"  Total Acueducto", 'LBTR',0,0,'L',true);
                $pdf->SetFont('dejavusans','B',11);
                $pdf->Cell(29,6,"$".number_format($factura->copago_acu+$factura->subsidio_acu). '  '. '', 'LRTB',0,'R',true);
                $pdf->Cell(1);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(45,6,"  Total Alcantarillado", 'LBTR',0,0,'L',true);
                $pdf->SetFont('dejavusans','B',11);
                $pdf->Cell(28,6,"$".number_format($factura->copago_alc+$factura->subsidio_alc). '  '. '', 'LRTB',0,'R',true);
                $pdf->Cell(1);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(35,6,"  Total Aseo", 'LBTR',0,0,'L',true);
                $pdf->SetFont('dejavusans','B',11);
                $pdf->Cell(30,6,"$".number_format($factura->copago_aseo+$factura->subsidio_aseo). '  '. '', 'LRTB',0,'R',true);
                $pdf->Cell(1);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(29,6,"  Total Ajuste", 'LBTR',0,0,'L',true);
                $pdf->SetFont('dejavusans','B',11);
                $pdf->Cell(25,6,"$".number_format($factura->ajuste). '  '. '', 'LRTB',0,'R',true);

                $pdf->Ln(8);
            
                $pdf->write1DBarcode($factura->numero_factura, 'C39', 17, '', '', 18, 0.6);
                $pdf->Cell(210);
                $pdf->SetLineWidth(0.1);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->SetFillColor(11,78,187);
                $pdf->SetDrawColor(11,78,187);
                $pdf->SetTextColor(255,255,255);
                

                $pdf->Cell(54,6,"TOTAL A PAGAR",1,0,'C',true);
                $pdf->Ln(6);
                $pdf->SetFillColor(255,255,255);
                $pdf->SetDrawColor(11,78,187);
                $pdf->SetTextColor(0 , 0, 0);
                $pdf->Cell(210);
                $pdf->SetFont('dejavusans','B',19);
                $pdf->Cell(54,12,'$'.number_format($factura->copago+$factura->saldo_anterior-$factura->ajuste),1,0,'C',true);
                $pdf->Ln(11);
                $pdf->MultiCell(0,9,"---------------------------------------------------------------------------------------------",0,'C',false);
                
                /* Informacion Recibo */

                $pdf->Ln(0);

                
            
                
                // $pdf->Image('../public/build/img/logo.png',17,145,52,32,'PNG');


                // $pdf->Cell(70);
                $pdf->SetLineWidth(0.1);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->SetFillColor(11,78,187);
                $pdf->SetDrawColor(11,78,187);
                $pdf->SetTextColor(255,255,255);
                $pdf->Cell(130,6,"Información del Cliente",1,0,'C',true);
                $pdf->Cell(3);
                $pdf->Cell(76,6,"Información de la Factura",1,0,'C',true);


                $pdf->Cell(1);
                $pdf->Cell(27,6,"Factura No",1,0,'L',true);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->SetDrawColor(11,78,187);
                $pdf->SetTextColor(255,0,0);
                $pdf->Cell(27,6,$factura->numero_factura,1,0,'C',true);

            
                
            

                $pdf->SetFillColor(255,255,255);
                $pdf->SetDrawColor(11,78,187);
                $pdf->SetTextColor(0,0,0);

                $pdf->Ln(6);

                
            

                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(30,6,"  Usuario: ", 'L',0,0,'L',true);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(100,6,"$registrado->nombre $registrado->apellido", 'R',0,0,'L',true);

                $pdf->Cell(3);
                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(37,6,"  Mes Factuardo",1,0,'L',true);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(39,6,$factura->mes_facturado.'  ',1,0,'R',true);

            

                
                
                
                $pdf->Ln(6);

                
                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(30,6,"  Cedula Nit: ", 'L',0,0,'L',true);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(100,6,"$registrado->cedula_nit", 'R',0,0,'L',true);
                $pdf->Cell(3);
                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(37,6,"  Facturas Vencidas",1,0,'L',true);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(39,6,$registrado->facturas.'  ',1,0,'R',true);

                $pdf->Cell(1);

                $pdf->SetLineWidth(0.1);
                $pdf->SetFont('dejavusans', 'B', 10);
                $pdf->SetFillColor(11, 78, 187);
                $pdf->SetDrawColor(11, 78, 187);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->Cell(54, 6, "TOTAL A PAGAR", 1, 0, 'C', true);


                $pdf->Ln(6);

                $pdf->SetFillColor(255,255,255);
                $pdf->SetDrawColor(11, 78, 187);
                $pdf->SetTextColor(0, 0, 0);
                
                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(30,6,"  Dirección: ", 'L',0,0,'L',true);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(100,6,"$registrado->direccion", 'R',0,0,'L',true);
            
                $pdf->Cell(3);
                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(37,6,"  Saldo Anterior",1,0,'L',true);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(39,6,'$'.number_format($factura->saldo_anterior).'  ',1,0,'R',true);

                $pdf->Ln(6);

                
                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(30,6,"  Estrato: ", 'LB',0,0,'L',true);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(100,6,"$factura->estrato", 'RB',0,0,'L',true);

                $pdf->Cell(3);
            
                $pdf->SetFont('dejavusans','',10);
                $pdf->Cell(37,6,"  Pago Mensual",1,0,'L',true);
                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(39,6,'$'.number_format($factura->copago-$factura->ajuste).'  ',1,0,'R',true);


                
            
                
                $pdf->SetY($total_pagar_posicion); // Establece la posición vertical (ajusta según tus necesidades)
                // $pdf->Cell(210);
                
                
                $pdf->SetFillColor(255,255,255);
                $pdf->SetDrawColor(11,78,187);
                $pdf->SetTextColor(0 , 0, 0);
                $pdf->Cell(210);
                $pdf->SetFont('dejavusans','B',19);
                $pdf->Cell(54,12,'$'.number_format($factura->copago+$factura->saldo_anterior-$factura->ajuste),1,0,'C',true);

                $pdf->Ln(15);

                $pdf->SetFont('dejavusans','B',10);
                $pdf->Cell(264,6,"Punto de pago, oficina principal de ASUAAASTAB ubicada en la antigua Alcaldía ",1,0,'C',true);
                $pdf->Ln(6);

                
                $pdf->Cell(264,6,"Vigilada Superintendencia de Servicios Públicos",1,0,'C',true);

                if($key%2==0){
                    $pdf->Ln(20);
                    $pdf->MultiCell(0,9,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------",0,'C',false);
                    $pdf->Ln(1);
                }


                    

            }

            
            $pdf->Output('example_027.pdf', 'I');
            
        }

        public static function generarFacturasManual(){


            $factura_mes_anterior = Factura::get(1);
            if($factura_mes_anterior){
                $fecha_emision =date("Y-m",strtotime($factura_mes_anterior->fecha_emision)) ;
                $fecha_actual = date("Y-m");
              
                if($fecha_emision == $fecha_actual){
                    echo json_encode(['type'=>'error', 'msg'=>'Las facturas ya han sido generadas con anterioridad']);
                    return;
                }
            }
            
            ob_clean();
         
            $ultimo_numero = 0;
            if($factura_mes_anterior){
                $ultimo_numero = $factura_mes_anterior->numero_factura;
            }else{
                $ultimo_numero = 1000;
            }

            
            $mes_facturado = date('Y-m', strtotime('-1 month'));
            $fecha_emision = date('Y-m-d');
            $mes_facturado = $mes_facturado.'-01';


      
            // $factura = new Factura();
            $registrados = Registrado::all();

            foreach($registrados as $registrado){

                

                if(!$registrado->estrato_id) continue;
                if($registrado->estado==0) continue;

               


                $registrado->facturas = $registrado->facturas + 1;
                $registrado->guardar();
                
                $ultimo_numero = $ultimo_numero +1;

                $estrato = Estrato::find($registrado->estrato_id);

                $saldo_anterior = 0;

                if($factura_mes_anterior){

                    if(($registrado->facturas-1)>0){
                        $factura_anterior = Factura::whereDes( 'registrado_id',$registrado->id);
                        $copago_anterior =  $factura_anterior->copago;
                        $ajuste_anterior = $factura_anterior->ajuste??0;
                        $saldo_anterior = $copago_anterior+$factura_anterior->saldo_anterior - $ajuste_anterior;
                        $factura_anterior->combinado = 1;
                        $factura_anterior->guardar();
                    }
                }
         

                
                
              
                $datos_factura = [
                    'numero_factura'=>$ultimo_numero,
                    'registrado_id'=>$registrado->id,
                    'mes_facturado'=>$mes_facturado,
                    'fecha_emision'=>$fecha_emision,
                    'estrato'=>$estrato->estrato,
                    'tarifa_plena'=>$estrato->tarifa_plena,
                    'subsidio'=>$estrato->subsidio,
                    'copago'=>$estrato->copago,
                    'subsidio_acu'=>$estrato->subsidio_acu,
                    'copago'=>$estrato->copago,
                    'copago_acu'=>$estrato->copago_acu,
                    'subsidio_alc'=>$estrato->subsidio_alc,
                    'copago_alc'=>$estrato->copago_alc,
                    'subsidio_aseo'=>$estrato->subsidio_aseo,
                    'copago_aseo'=>$estrato->copago_aseo,
                    'ajuste'=>$estrato->ajuste??0,
                    'pagado'=>0,
                    'combinado' =>0,
                    'saldo_anterior' =>$saldo_anterior


                ];

           
                $factura = new Factura();
                $factura->sincronizar($datos_factura);
               
                $resultado =  $factura->guardar();
          
    
                
               
               
            }
            echo json_encode(['type'=>'success', 'msg'=>'facturas Generadas Exitosamente']);
            return;

          
         
          
        }

        public static function generarFacturas(){


            $factura = Factura::get(1);
            
         
            $ultimo_numero = 0;
            if($factura){
                $ultimo_numero = $factura->numero_factura;
            }else{
                $ultimo_numero = 1000;
            }

            
            $mes_facturado = date('Y-m', strtotime('-1 month'));
            $fecha_emision = date('Y-m-d');
            $mes_facturado = $mes_facturado.'-01';


      
            $factura = new Factura();
            $registrados = Registrado::all();

            foreach($registrados as $registrado){

                

                if(!$registrado->estrato_id) continue;
                if($registrado->estado==0) continue;

                $registrado->facturas = $registrado->facturas + 1;
                $registrado->guardar();
                
                $ultimo_numero = $ultimo_numero +1;

                $estrato = Estrato::find($registrado->estrato_id);

                $datos_factura = [
                    'numero_factura'=>$ultimo_numero,
                    'registrado_id'=>$registrado->id,
                    'mes_facturado'=>$mes_facturado,
                    'fecha_emision'=>$fecha_emision,
                    'estrato'=>$estrato->estrato,
                    'tarifa_plena'=>$estrato->tarifa_plena,
                    'subsidio'=>$estrato->subsidio,
                    'copago'=>$estrato->copago,
                    'subsidio_acu'=>$estrato->subsidio_acu,
                    'copago'=>$estrato->copago,
                    'copago_acu'=>$estrato->copago_acu,
                    'subsidio_alc'=>$estrato->subsidio_alc,
                    'copago_alc'=>$estrato->copago_alc,
                    'subsidio_aseo'=>$estrato->subsidio_aseo,
                    'copago_aseo'=>$estrato->copago_aseo,
                    'ajuste'=>$estrato->ajuste??0,
                    'pagado'=>0


                ];

           
               
                $factura->sincronizar($datos_factura);
               
                $resultado = $factura->guardar();
                echo "<pre>";
                var_dump($resultado);
                echo "</pre>";
               
            }
            return;
        }
       

        public static function facturasRegistrado(){
            if(!is_auth()){
                header('Location:/login');
            }
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if(!$id){
                header('Location:/admin/registrados');
            }
          
            $registrado = Registrado::find($id);
            if(!$registrado){
                header('Location:/admin/registrados');
            }
           
            $facturas = Factura::whereArray(['registrado_id'=>$registrado->id]);
         
            
            foreach($facturas as $factura){
                // $factura->fecha_emision = date("d-m-Y", strtotime($factura->fecha_emision));

                $fechaInicioObj = strtotime($factura->mes_facturado);
                $fechaFinObj = strtotime(date('t-m-Y', $fechaInicioObj));

                $factura->periodo_inicio = date('d-m-Y', $fechaInicioObj);
                $factura->periodo_fin = date('d-m-Y', $fechaFinObj);
            }
            $deuda=0;
          
            foreach($facturas as $factura){
                if($factura->pagado ==0 && $factura->combinado==0){
                    $deuda = $factura->copago - $factura->ajuste +$factura->saldo_anterior;
                    // $deuda = $factura->copago + $deuda - $factura->ajuste;
                }
            }
          
            
            echo json_encode(['facturas'=>$facturas, 'deuda'=>$deuda]);

        }

        public static function previsualizarFactura(){
           
            if(!is_auth()){
                header('Location:/login');
            }
            
           
            $id = base64_decode($_GET['key']);
      
            $id = filter_var($id, FILTER_VALIDATE_INT);
      
            if(!$id){
               
                echo json_encode(['type'=>'error','res'=>'Hubo un error, Intenta Nuevamente s' ]);
                return;
            }

            $factura = Factura::where('numero_factura', $id);

            if(!$factura){
                echo json_encode(['type'=>'error','res'=>'Hubo un error, Intenta Nuevamente d' ]);
                return;
            }

          
            $factura->fecha_emision =date('Y-m',strtotime($factura->fecha_emision));
        

       
      
        

            $registrado = Registrado::find($factura->registrado_id);
       
            $facturas_vencidas = $registrado->facturas;
            if($registrado->facturas>0){
                $facturas_vencidas = $registrado->facturas-1;
            }
           
        

        
            mb_internal_encoding('UTF-8');
            // $factura->formatearDatosNumber();

            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            $pdf->setPrintHeader(false);
          
            $pdf->AddPage('p', 'A3');

           
           
     
            $pdf->SetMargins(17,10, 17);
            // # Logo de la empresa formato png #

             $pdf->Image('../public/build/img/logo.png',17,10,52,38,'PNG');
                // Relleno rojo (RGB)
             
         
             $pdf->Ln(0);
           
          
            

            // # Encabezado y datos de la empresa #
            $pdf->Cell(87);
            $pdf->SetFont('dejavusans','B',11);
            $pdf->SetTextColor(11,78,187);
            $pdf->Cell(150,10,'Asociación de Usuarios Administradores de Acueducto, Alcantarillado y Aseo',0,0,'C');

            $pdf->Ln(5);
            $pdf->Cell(87);
            $pdf->Cell(150,10,'del Casco Urbano de El Tablón de Gómez',0,0,'C');
            $pdf->Ln(6);
            $pdf->Cell(87);
            $pdf->SetFont('dejavusans','B',14);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(150,10,'ASUAAASTAB',0,0,'C');
            $pdf->Ln(6);
            $pdf->Cell(87);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(150,10,'Nit: 900324139-0',0,0,'C');
            $pdf->Ln(6);
            $pdf->Cell(87);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(150,10,'3216549877',0,0,'C');
            $pdf->Ln(6);
            $pdf->Cell(87);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(150,10,'El Tablón de Gómez',0,0,'C');

            $pdf->Ln(10);

              //informacion cCliente
              $pdf->SetLineWidth(0.1);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->SetFillColor(11,78,187);
            $pdf->SetDrawColor(11,78,187);
            $pdf->SetTextColor(255,255,255);
            $pdf->Cell(130,7,"Información del Cliente",1,0,'C',true);
            $pdf->Cell(3);
            $pdf->Cell(76,7,"Información de la Factura",1,0,'C',true);
            $pdf->Cell(1);
            $pdf->Cell(27,7,"Factura No",1,0,'L',true);
           
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetDrawColor(11,78,187);
            $pdf->SetTextColor(255,0,0);
            $pdf->Cell(27,7,$factura->numero_factura,1,0,'C',true);
            

            $pdf->Cell(3);
       

            $pdf->Ln(7);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0,0,0);

            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(30,6,"  Usuario: ", 'L',0,0,'L',true);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(100,6,"$registrado->nombre $registrado->apellido", 'R',0,0,'L',true);
            $pdf->Cell(3);
 
            $pdf->SetDrawColor(11,78,187);
            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(76,6,"  Mes Factuardo",1,0,'L',true);
            $pdf->Cell(1);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(54,6,date('Y-m',strtotime($factura->mes_facturado)).'  ',1,0,'R',true);


            $pdf->Ln(6);

         
            $year = date('Y',strtotime($factura->fecha_emision)); // Año con 4 dígitos
            $month = date('m',strtotime($factura->fecha_emision)); // Mes con 2 dígitos

            // Calcular el último día del mes
            $lastDay = date('t', strtotime("$year-$month-01"));

            // Crear la fecha completa con el último día del mes
            $lastDayOfMonth = date("$year-$month-$lastDay");

            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(30,6,"  Cedula/Nit: ", 'L',0,0,'L',true);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(100,6,"$registrado->cedula_nit", 'R',0,0,'L',true);
            $pdf->SetDrawColor(11,78,187);
            $pdf->Cell(3);
            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(76,6,"  Fecha de Pago Oportuno",1,0,'L',true);
            $pdf->Cell(1);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(54,6,$lastDayOfMonth. '  ',1,0,'R',true);

     

            $pdf->Ln(6);
            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(30,6,"  Dirección:", 'L',0,0,'L',true);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(100,6,"$registrado->direccion", 'R',0,0,'L',true);
            $pdf->Cell(3);
            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(76,6,"  Facturas Vencidas",1,0,'L',true);
            $pdf->Cell(1);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(54,6,$facturas_vencidas. '  ',1,0,'R',true);
            $pdf->Ln(6);
            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(30,6,"  Barrio: ", 'L',0,0,'L',true);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(100,6,"$registrado->barrio", 'R',0,0,'L',true);
            $pdf->Cell(3);
            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(76,6,"  Saldo Anterior",1,0,'L',true);
            $pdf->Cell(1);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(54,6,'$'.number_format($factura->saldo_anterior). '  ',1,0,'R',true);
            
            $pdf->SetFont('dejavusans','',10);
            $pdf->Ln(6);
            $pdf->Cell(30,6,"  Código: ", 'L',0,0,'L',true);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(100,6,"$registrado->codigo_ubicacion", 'R',0,0,'L',true);
            $pdf->Cell(3);
            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(76,6,"  Pago Mensual",1,0,'L',true);
            $pdf->Cell(1);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(54,6,'$'.number_format($factura->copago-$factura->ajuste). '  ',1,0,'R',true);
            $pdf->Ln(6);
            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(30,6,"  Estrato: ", 'LB',0,0,'L',true);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(100,6,"$factura->estrato", 'RB',0,0,'L',true);
            $pdf->Cell(3);
            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(76,6,"  Total Pagar",1,0,'L',true);
            $pdf->Cell(1);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(54,6,'$'.number_format($factura->copago-$factura->ajuste+$factura->saldo_anterior). '  ',1,0,'R',true);

          /* infomraicon acueducto aseo, alcantarillado y pago fina */
            $pdf->Ln(8);
            $pdf->SetLineWidth(0.1);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->SetFillColor(11,78,187);
            $pdf->SetDrawColor(11,78,187);
            $pdf->SetTextColor(255,255,255);
            $pdf->Cell(69,6,"Liquidación Acueducto",1,0,'C',true);
            $pdf->Cell(1);
            $pdf->Cell(73,6,"Liquidación Alcantarillado",1,0,'C',true);
            $pdf->Cell(1);
            $pdf->Cell(65,6,"Liquidación Aseo",1,0,'C',true);
            $pdf->Cell(1);
            $pdf->Cell(54,6,"Ajuste",1,0,'C',true);

            $pdf->Ln(6);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0,0,0);

            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(24,6,"  Cargo Fijo: ", 'L',0,0,'L',true);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(45,6,"$".number_format($factura->copago_acu). '  ', 'R',0,'R',true);

             $pdf->Cell(1);
             $pdf->SetFont('dejavusans','',10);
             $pdf->Cell(25,6,"  Cargo Fijo: ", 'L',0,0,'L',true);
             $pdf->SetFont('dejavusans','B',10);
             $pdf->Cell(48,6,"$".number_format($factura->copago_alc). '  ', 'R',0,'R',true);
             $pdf->Cell(1);
             $pdf->SetFont('dejavusans','',10);
             $pdf->Cell(25,6,"  Cargo Fijo: ", 'L',0,0,'L',true);
             $pdf->SetFont('dejavusans','B',10);
             $pdf->Cell(40,6,"$".number_format($factura->copago_aseo). '  ', 'R',0,'R',true);
             $pdf->Cell(1);
             $pdf->SetFont('dejavusans','',10);
             $pdf->Cell(22,6,"  Tarifario: ", 'L',0,0,'L',true);
             $pdf->SetFont('dejavusans','B',10);
             $pdf->Cell(32,6,"$".number_format($factura->copago_aseo). '  ', 'R',0,'R',true);

            $pdf->Ln(6);
            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(24,6,"  Subsidiado: ", 'LB',0,0,'L',true);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(45,6,"$".number_format($factura->subsidio_acu). '  ', 'RB',0,'R',true);
            $pdf->Cell(1);
            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(25,6,"  Subsidiado: ", 'L',0,0,'L',true);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(48,6,"$".number_format($factura->subsidio_alc). '  ', 'R',0,'R',true);
            $pdf->Cell(1);
            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(25,6,"  Subsidiado: ", 'L',0,0,'L',true);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(40,6,"$".number_format($factura->subsidio_aseo). '  ', 'R',0,'R',true);
            $pdf->Cell(1);
            $pdf->Cell(54,6,"", 'LR',0,'R',true);
            $pdf->Ln(6);

            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(40,6,"  Total Acueducto", 'LBTR',0,0,'L',true);
            $pdf->SetFont('dejavusans','B',11);
            $pdf->Cell(29,6,"$".number_format($factura->copago_acu+$factura->subsidio_acu). '  '. '', 'LRTB',0,'R',true);
            $pdf->Cell(1);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(45,6,"  Total Alcantarillado", 'LBTR',0,0,'L',true);
            $pdf->SetFont('dejavusans','B',11);
            $pdf->Cell(28,6,"$".number_format($factura->copago_alc+$factura->subsidio_alc). '  '. '', 'LRTB',0,'R',true);
            $pdf->Cell(1);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(35,6,"  Total Aseo", 'LBTR',0,0,'L',true);
            $pdf->SetFont('dejavusans','B',11);
            $pdf->Cell(30,6,"$".number_format($factura->copago_aseo+$factura->subsidio_aseo). '  '. '', 'LRTB',0,'R',true);
            $pdf->Cell(1);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(29,6,"  Total Ajuste", 'LBTR',0,0,'L',true);
            $pdf->SetFont('dejavusans','B',11);
            $pdf->Cell(25,6,"$".number_format($factura->ajuste). '  '. '', 'LRTB',0,'R',true);

            $pdf->Ln(8);
       
            $pdf->write1DBarcode($factura->numero_factura, 'C39', 17, '', '', 18, 0.6);
            $pdf->Cell(210);
            $pdf->SetLineWidth(0.1);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->SetFillColor(11,78,187);
            $pdf->SetDrawColor(11,78,187);
            $pdf->SetTextColor(255,255,255);
            
    
            $pdf->Cell(54,6,"TOTAL A PAGAR",1,0,'C',true);
            $pdf->Ln(6);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetDrawColor(11,78,187);
            $pdf->SetTextColor(0 , 0, 0);
            $pdf->Cell(210);
            $pdf->SetFont('dejavusans','B',19);
            $pdf->Cell(54,12,'$'.number_format($factura->copago+$factura->saldo_anterior-$factura->ajuste),1,0,'C',true);
            $pdf->Ln(11);
            $pdf->MultiCell(0,9,"---------------------------------------------------------------------------------------------",0,'C',false);
            
            /* Informacion Recibo */

            $pdf->Ln(0);

            
     
            
            // $pdf->Image('../public/build/img/logo.png',17,145,52,32,'PNG');


            // $pdf->Cell(70);
            $pdf->SetLineWidth(0.1);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->SetFillColor(11,78,187);
            $pdf->SetDrawColor(11,78,187);
            $pdf->SetTextColor(255,255,255);
            $pdf->Cell(130,6,"Información del Cliente",1,0,'C',true);
            $pdf->Cell(3);
            $pdf->Cell(76,6,"Información de la Factura",1,0,'C',true);

   
            $pdf->Cell(1);
            $pdf->Cell(27,6,"Factura No",1,0,'L',true);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetDrawColor(11,78,187);
            $pdf->SetTextColor(255,0,0);
            $pdf->Cell(27,6,$factura->numero_factura,1,0,'C',true);

      
            
     

            $pdf->SetFillColor(255,255,255);
            $pdf->SetDrawColor(11,78,187);
            $pdf->SetTextColor(0,0,0);

            $pdf->Ln(6);

          
      
   
            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(30,6,"  Usuario: ", 'L',0,0,'L',true);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(100,6,"$registrado->nombre $registrado->apellido", 'R',0,0,'L',true);

            $pdf->Cell(3);
            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(37,6,"  Mes Factuardo",1,0,'L',true);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(39,6,$factura->mes_facturado.'  ',1,0,'R',true);

        

    
            
            $pdf->Ln(6);

          
            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(30,6,"  Cedula Nit: ", 'L',0,0,'L',true);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(100,6,"$registrado->cedula_nit", 'R',0,0,'L',true);
            $pdf->Cell(3);
            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(37,6,"  Facturas Vencidas",1,0,'L',true);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(39,6,$registrado->facturas.'  ',1,0,'R',true);

            $pdf->Cell(1);

            $pdf->SetLineWidth(0.1);
            $pdf->SetFont('dejavusans', 'B', 10);
            $pdf->SetFillColor(11, 78, 187);
            $pdf->SetDrawColor(11, 78, 187);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell(54, 6, "TOTAL A PAGAR", 1, 0, 'C', true);


            $pdf->Ln(6);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetDrawColor(11, 78, 187);
            $pdf->SetTextColor(0, 0, 0);
          
            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(30,6,"  Dirección: ", 'L',0,0,'L',true);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(100,6,"$registrado->direccion", 'R',0,0,'L',true);
       
            $pdf->Cell(3);
            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(37,6,"  Saldo Anterior",1,0,'L',true);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(39,6,'$'.number_format($factura->saldo_anterior).'  ',1,0,'R',true);

            $pdf->Ln(6);

            
            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(30,6,"  Estrato: ", 'LB',0,0,'L',true);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(100,6,"$factura->estrato", 'RB',0,0,'L',true);

            $pdf->Cell(3);
     
            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(37,6,"  Pago Mensual",1,0,'L',true);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(39,6,'$'.number_format($factura->copago-$factura->ajuste).'  ',1,0,'R',true);

    
         
             // Establece la posición horizontal (ajusta según tus necesidades)
            $pdf->SetY(164); // Establece la posición vertical (ajusta según tus necesidades)
            // $pdf->Cell(210);
           
            
            $pdf->SetFillColor(255,255,255);
            $pdf->SetDrawColor(11,78,187);
            $pdf->SetTextColor(0 , 0, 0);
            $pdf->Cell(210);
            $pdf->SetFont('dejavusans','B',19);
            $pdf->Cell(54,12,'$'.number_format($factura->copago+$factura->saldo_anterior-$factura->ajuste),1,0,'C',true);

            $pdf->Ln(15);

            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(264,6,"Punto de pago, oficina principal de ASUAAASTAB ubicada en la antigua Alcaldia ",1,0,'C',true);
            $pdf->Ln(6);

            
            $pdf->Cell(264,6,"Vigilada Superintendencia de Servicios Públicos",1,0,'C',true);



            $pdf->Output('example_027.pdf', 'I');
            //echo json_encode($registrado);
        }

        public static function eliminarFacturas(){
            $fecha = date('Y-m', strtotime('-1 month'));
            $facturas = Factura::fechas($fecha);

            

            if(!$facturas){
                echo json_encode(['type'=> 'error', 'msg'=>'Aun no se han generado las facturas del ulitmo mes;']);
                return;
            }
            ob_clean();
            foreach($facturas as $factura){
                if($factura->pagado ==1){
                    echo json_encode(['type'=> 'error', 'msg'=>'No es posible eliminar las facturas generadas porque ya hay pagos asociados a dichas facturas']);
                    return;
                }
            }

            ob_clean();

            $facturas = new Factura;
            $registrados = Registrado::all();

            foreach($registrados as $registrado){


                if(!$registrado->estrato_id) continue;
                if($registrado->estado==0) continue;

                $registrado->facturas = $registrado->facturas - 1;
                $registrado->guardar();
             
               
            }
            $fecha_actual = date('Y-m');
        
          
            $resultado = $facturas->eliminarLeft('fecha_emision', $fecha_actual, 7);
     
            if($resultado){
                echo json_encode(['type'=> 'success', 'msg'=>'Las facturas del ultimo mes han sido eliminadas exitosamente']);
                return;
            }
           
        }

       
    
    }
