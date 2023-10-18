<?php 

    namespace Controllers;

    use Model\Factura;
    use Model\Estrato;
        use FPDF;
    use Model\Caja;
    use Model\CajaPago;
    use Model\Pago;
    use Model\Registrado;
    use Model\Usuario;
use TCPDF;

    class ApiPagos{

        

        public static function pagosRegistrado(){
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
           
            $pagos = Pago::whereArray(['registrado_id'=>$registrado->id]);
            
            foreach($pagos as $pago){
                $factura = Factura::find($pago->factura_id);
                $fechaInicioObj = strtotime($factura->mes_facturado);
                $fechaFinObj = strtotime(date('t-m-Y', $fechaInicioObj));

                $pago->periodo_inicio = date('d-m-Y', $fechaInicioObj);
                $pago->periodo_fin = date('d-m-Y', $fechaFinObj);
                $pago->monto = number_format($factura->copago+$factura->saldo_anterior-$factura->ajuste);
                $pago->numero_factura = $factura->numero_factura;
                $pago->estrato = $factura->estrato;

                $usuario = Usuario::find($pago->usuario_id);
                $pago->recaudador = $usuario->nombre. ' '. $usuario->apellido;
                
            }
           
       
            echo json_encode($pagos);

        }


        public static function pagar(){
           

            if(!is_auth()){
                header('Location:/login');
            }



            $caja = Caja::get(1);
            if(!$caja){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Para realizar un pago debe abrir una caja']);
                return;
            }
            if($caja->estado == null){
                 echo json_encode(['tipo'=>'error', 'mensaje'=>'Para realizar un pago debe abrir una caja']);
                return;
            }
            if($caja->estado == 0){
                 echo json_encode(['tipo'=>'error', 'mensaje'=>'Para realizar un pago debe abrir una caja']);
                return;
            }

            
            $id = $_POST['id'];
       
            if(!$id){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un Error, Porfavor intenta nuevamente']);
                return;
            }
            $factura = Factura::find($id);
            if(!$factura){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un Error, Porfavor intenta nuevamente']);
                return;
            }
            $factura->pagado = 1;
            $resultado = $factura->guardar();
            if(!$resultado){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un Error, Porfavor intenta nuevamente']);
                return;
            }
            $registrado = Registrado::find($factura->registrado_id);
            if(!$registrado){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un Error, Porfavor intenta nuevamente']);
                return;
            }
            $registrado->facturas =0;


            $resultado = $registrado->guardar();
            if(!$resultado){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un Error, Porfavor intenta nuevamente']);
                return;
            }
            $pago = Pago::get(1);
         
            $numero_pago = 0;
            if($pago){
                $numero_pago = $pago->numero_pago + 1;
            }else{
                $numero_pago = 200000;
            }

            $arrayPago = [
                'numero_pago'=> $numero_pago,
                'fecha_pago'=>date('Y-m-d'),
                'metodo'=> $_POST['metodo'],
                'estado'=>1,
                'factura_id'=>$factura->id, 
                'registrado_id'=>$factura->registrado_id,
                'usuario_id'=>$_SESSION['id']

            ];

            $pago = new Pago($arrayPago);
            
            $resultado = $pago->guardar();
            if(!$resultado){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un Error, Porfavor intenta nuevamente']);
                return;
            }
            $arrayCajaPagos = [
                'caja_id' => $caja->id,
                'pago_id'=> $resultado['id']
            ];

            $cajaPagos = new CajaPago($arrayCajaPagos);
            $resultado = $cajaPagos->guardar();

        
            if(!$resultado){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un Error, Porfavor intenta nuevamente']);
                return;
            }

            echo json_encode(['tipo'=>'success', 'mensaje'=>'Su Factura ha sido Pagada Porrectamente']);
            return;
        }

        public static function previsualizarPago(){
            if(!is_auth()){
                header('Location:/login');
            }
            
           
            $id = base64_decode($_GET['key']);
      
            $id = filter_var($id, FILTER_VALIDATE_INT);
      
            if(!$id){
               
                echo json_encode(['type'=>'error','res'=>'Hubo un error, Intenta Nuevamente s' ]);
                return;
            }

       
            $pago = Pago::where('factura_id', $id);

            if(!$pago){
                echo json_encode(['type'=>'error','res'=>'Hubo un error, Intenta Nuevamente d' ]);
                return;
            }
            
            $factura = Factura::find($pago->factura_id);
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
            $pdf->Cell(150,10,'Asociación de usuarios Administradores de Acueducto, Alcantarillado y Aseo',0,0,'C');

            $pdf->Ln(5);
            $pdf->Cell(87);
            $pdf->Cell(150,10,'del Casco Urbano del Tablón de Gómez',0,0,'C');
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
            $pdf->Cell(150,10,'El Tablon de Gómez',0,0,'C');

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
            $pdf->Cell(76,6,"  Fecha de Pago",1,0,'L',true);
            $pdf->Cell(1);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(54,6,$pago->fecha_pago.'  ',1,0,'R',true);


            $pdf->Ln(6);

            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(30,6,"  Cedula/Nit: ", 'L',0,0,'L',true);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(100,6,"$registrado->cedula_nit", 'R',0,0,'L',true);
            $pdf->SetDrawColor(11,78,187);
            $pdf->Cell(3);
            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(76,6,"  Mes Facturado",1,0,'L',true);
            $pdf->Cell(1);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(54,6,date('Y-m',strtotime($factura->mes_facturado)).'  ',1,0,'R',true);

     

            $pdf->Ln(6);
            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(30,6,"  Dirección:", 'L',0,0,'L',true);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(100,6,"$registrado->direccion", 'R',0,0,'L',true);
            $pdf->Cell(3);
            $pdf->SetFont('dejavusans','',10);
            $pdf->Cell(76,6,"  Facturas Pagadas",1,0,'L',true);
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
            $pdf->Cell(76,6,"  Total Pagado",1,0,'L',true);
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
       
            $pdf->SetLineWidth(0.1);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->SetFillColor(11,78,187);
            $pdf->SetDrawColor(11,78,187);
            $pdf->SetTextColor(255,255,255);
            if($pago->estado==1){
                $pdf->Cell(54,6,"NUMERO DE PAGO",1,0,'C',true);
            }else{
                $pdf->Cell(54,6,"PAGO ANULADO",1,0,'C',true);
            }
          
            $pdf->SetFillColor(255,255,255);
            $pdf->SetDrawColor(255,255,255);
            $pdf->Cell(156,6,"",1,0,'C',true);
            
            $pdf->SetLineWidth(0.1);
            $pdf->SetFont('dejavusans','B',10);
            $pdf->SetFillColor(11,78,187);
            $pdf->SetDrawColor(11,78,187);
            $pdf->SetTextColor(255,255,255);
            
    
            $pdf->Cell(54,6,"TOTAL PAGADO",1,0,'C',true);



            $pdf->Ln(6);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetDrawColor(11,78,187);
            $pdf->SetTextColor(255 , 0, 0);

            $pdf->SetFont('dejavusans','B',19);

            
            $pdf->Cell(53.5,12,$pago->numero_pago,1,0,'C',true);

            $pdf->Cell(1);
          
            $pdf->SetFillColor(255,255,255);
            $pdf->SetDrawColor(255,255,255);
          

            $pdf->Cell(155.5,12,"",1,0,'C',true);
      

            $pdf->SetFillColor(255,255,255);
            $pdf->SetDrawColor(11,78,187);
            $pdf->SetTextColor(0 , 0, 0);
     
            $pdf->SetFont('dejavusans','B',19);
            $pdf->Cell(53.9,12,'$'.number_format($factura->copago+$factura->saldo_anterior-$factura->ajuste),1,0,'C',true);
          
            $pdf->Ln(15);

            $pdf->SetFont('dejavusans','B',10);
            $pdf->Cell(264,6,"Punto de pago, oficina principal de ASUAAASTAB ubicada en la antigua Alcaldia ",1,0,'C',true);
            $pdf->Ln(6);

            
            $pdf->Cell(264,6,"Vigilada Superintendencia de Servicios Públicos",1,0,'C',true);
            


            $pdf->Output('$comprobante_'.$pago->numero_pago.'.pdf', 'I');
            //echo json_encode($registrado);
            //echo json_encode($registrado);
        }

        public static function anularPago(){
            if(!is_auth()){
                header('Location:/login');
            }
            $numero_pago = $_POST['numero_pago'];
            $numero_pago = filter_var($numero_pago, FILTER_VALIDATE_INT);
            if(!$numero_pago){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un Error, Porfavor intenta nuevamente']);
                return;
            }
            $pago = Pago::where('numero_pago', $numero_pago);
           if(!$pago){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un Error, Porfavor intenta nuevamente']);
                return;
           }
           $factura = Factura::find($pago->factura_id);
           $registrado = Registrado::find($factura->registrado_id);
           $registrado->facturas = round(($factura->copago+$factura->saldo_anterior -$factura->ajuste)/($factura->copago-$factura->ajuste));

   
         
           $registrado->guardar();
        
           $factura->pagado = 0;
           $resultado = $factura->guardar();
           if(!$resultado){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un Error, Porfavor intenta nuevamente']);
                return;
           }
           $pago->estado = 0;
           $resultado = $pago->guardar();
           if(!$resultado){
                echo json_encode(['tipo'=>'error', 'mensaje'=>'Hubo un Error, Porfavor intenta nuevamente']);
                return;
           }
           
           echo json_encode(['tipo'=>'success', 'mensaje'=>'Su Anulado el Pago Correctamente']);
           return;
        }
    }
    