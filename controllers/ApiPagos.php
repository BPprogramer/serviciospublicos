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
                $pago->monto = number_format($factura->copago-$factura->ajuste);
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
            $registrado->facturas = $registrado->facturas - 1;


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
                $numero_pago = 50000;
            }

            $arrayPago = [
                'numero_pago'=> $numero_pago,
                'fecha_pago'=>date('Y-m-d'),
                'metodo'=> $_POST['metodo'],
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

            $pago = Pago::where('numero_pago', $id);

            if(!$pago){
                echo json_encode(['type'=>'error','res'=>'Hubo un error, Intenta Nuevamente d' ]);
                return;
            }
            
            $factura = Factura::find($pago->factura_id);
            if(!$factura){
                echo json_encode(['type'=>'error','res'=>'Hubo un error, Intenta Nuevamente d' ]);
                return;
            }

   
            //calculamos el total a pagar copago - ajuste
            $total = number_format($factura->copago - $factura->ajuste);

            //formatear periodo de facturacion
            $factura->fecha_emision = date("d-m-Y", strtotime($factura->fecha_emision));
            $fechaInicioObj = strtotime($factura->mes_facturado);
            $fechaFinObj = strtotime(date('t-m-Y', $fechaInicioObj));
        
            $factura->periodo_inicio = date('d-m-Y', $fechaInicioObj);
            $factura->periodo_fin = date('d-m-Y', $fechaFinObj);

            //fecha limite
           
            $factura->fecha_limite = date('d-m-y',strtotime(date('t-m-Y', strtotime($factura->fecha_emision))));
      
        

            $registrado = Registrado::find($factura->registrado_id);
            

            // $estrato = Estrato::find($factura->registrado_id);
            
            mb_internal_encoding('UTF-8');
            $factura->formatearDatosNumber();



            $pdf = new FPDF('P','mm','Letter');
         
            $pdf->SetMargins(17,10,17);
            $pdf->AddPage();

            # Logo de la empresa formato png #
            $pdf->Image('../public/build/img/logo.png',97,9,100,25,'PNG');

       

            # Encabezado y datos de la empresa #
            // $pdf->SetFont('Arial','B',16);
            // $pdf->SetTextColor(32,100,210);
            // $pdf->Cell(150,10,(strtoupper("Servicios Publicos")),0,0,'L');

            $pdf->Ln(5);

            $pdf->SetFont('Arial','',10);
            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(150,8,("Empresa: serviciosPublicos"),0,0,'L');
            $pdf->Ln(5);

            $pdf->SetFont('Arial','',10);
            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(150,8,("RUT: 0000000000"),0,0,'L');

            $pdf->Ln(5);

            $pdf->Cell(150,8,utf8_decode("Dirección San Salvador, El Salvador"),0,0,'L');

            $pdf->Ln(5);

            $pdf->Cell(150,8,utf8_decode("Teléfono: 00000000"),0,0,'L');

            $pdf->Ln(5);

            $pdf->Cell(93,8,utf8_decode("Email: correo@ejemplo.com"),0,0,'L');

            $pdf->Ln(12);

            $pdf->SetFont('Arial','',15);
            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(44, 8, utf8_decode("Comprobante No:"), 0, 0);
            $pdf->SetTextColor(255,0,0);
            $pdf->SetFont('Arial','',20);
            $pdf->Cell(64,8,($pago->numero_pago),0,0,'L');


            $pdf->Ln(8);

          

           
            
            $pdf->SetFont('Arial','',15);
            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(29, 8, utf8_decode("Factura No:"), 0, 0);
            $pdf->SetTextColor(255,0,0);
            $pdf->SetFont('Arial','',20);
            $pdf->Cell(64,8,($factura->numero_factura),0,0,'L');

            $pdf->Ln(10);

            $pdf->SetFont('Arial','',10);
            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(31,8,utf8_decode("Periodo Facturado:"),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(116,8,($factura->periodo_inicio.'  -  '.$factura->periodo_fin),0,0,'L');
            $pdf->Ln(5);

            $pdf->SetFont('Arial','',10);
            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(26,8,utf8_decode("Fecha de Pago:"),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(26,8,($pago->fecha_pago),0,0,'L');
   

            $pdf->Ln(10);

            $pdf->SetFont('Arial','',10);
            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(13,8,("Cliente:"),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(81,8,utf8_decode("$registrado->nombre $registrado->apellido"),0,0,'L');

            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(20,8,("Nit / Cedula: "),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(73,8,utf8_decode($registrado->cedula_nit),0,0);
         

            $pdf->Ln(5);

            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(13,8,("Celular: "),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(81,8,utf8_decode($registrado->celular),0,0);

            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(11,8,("Barrio: "),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(80,8,utf8_decode($registrado->barrio),0,0);
        
            $pdf->Ln(5);

        
            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(16,8,utf8_decode("Dirección: "),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(78,8,($registrado->direccion),0,0);

            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(13,8,utf8_decode("Código: "),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(80,8,($registrado->codigo_ubicacion),0,0);


            $pdf->Ln(10);
          
            $pdf->SetTextColor(39,39,51);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(15,7,("Estrato:"),0,0,'L');
  
            $pdf->SetTextColor(97,97,97);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(80,8,utf8_decode("$factura->estrato"),0,0);

            $pdf->Ln(9);

            # Tabla de productos #
            $pdf->SetFont('Arial','',8);
            $pdf->SetFillColor(23,83,201);
            $pdf->SetDrawColor(255,255,255);
            $pdf->SetTextColor(255,255,255);
            
            $pdf->Cell(45,8,("Acueducto"),1,0,'C',true);
            $pdf->Cell(45,8,("Alcantariilado."),1,0,'C',true);
            $pdf->Cell(45,8,("Aseo"),1,0,'C',true);
            $pdf->Cell(46,8,("Tarifa Plena."),1,0,'C',true);

            $pdf->Ln(8);

         
  
            $pdf->Cell(23,8,utf8_decode("Descripción"),1,0,'C',true);
            $pdf->Cell(22,8,("Total"),1,0,'C',true);
            $pdf->Cell(23,8,utf8_decode("Descripción"),1,0,'C',true);
            $pdf->Cell(22,8,("Total"),1,0,'C',true);
            $pdf->Cell(23,8,utf8_decode("Descripción"),1,0,'C',true);
            $pdf->Cell(22,8,("Total"),1,0,'C',true);
   
            $pdf->Cell(23,8,("Concepto"),1,0,'C',true);
            $pdf->Cell(23,8,("Valor"),1,0,'C',true);

            $pdf->Ln(8);


            $pdf->SetFillColor(23,83,201);
            $pdf->SetDrawColor(23,83,201);
            $pdf->SetTextColor(39,39,51);

            $pdf->SetX(17.3);
            /*----------  Detalles de la tabla  ----------*/
            $pdf->Cell(22.7,8,(""),'LT',0,'C');
            $pdf->Cell(22.,8,(""),'LT',0,'C');
            $pdf->Cell(22.9,8,(""),'LT',0,'C');
            $pdf->Cell(22.,8,(""),'LT',0,'C');
            $pdf->Cell(23,8,(""),'LT',0,'C');
            $pdf->Cell(22.,8,(""),'LT',0,'C');
            $pdf->Cell(23,8,("Tarifa Plena"),'LT',0,'C');
            $pdf->Cell(22.8,8,("$ $factura->tarifa_plena"),'LTR',0,'C');
          
            $pdf->Ln(7);


            $pdf->SetX(17.3);
            /*----------  Detalles de la tabla  ----------*/
            $pdf->Cell(22.7,8,("Subsidiado"),'LT',0,'C');
            $pdf->Cell(22.,8,("$ $factura->copago_acu"),'LT',0,'C');
            $pdf->Cell(22.9,8,("Subsidiado"),'LT',0,'C');
            $pdf->Cell(22.,8,("$ $factura->copago_alc"),'LT',0,'C');
            $pdf->Cell(23,8,("Subsidiado"),'LT',0,'C');
            $pdf->Cell(22.,8,("$ $factura->copago_aseo"),'LT',0,'C');
            $pdf->Cell(23,8,("Subsidio"),'LT',0,'C');
            $pdf->Cell(22.8,8,("$ $factura->copago"),'LTR',0,'C');
          
            $pdf->Ln(7);
            $pdf->SetX(17.3);
       
        
            /*----------  Fin Detalles de la tabla  ----------*/
            
            $pdf->Cell(22.7,8,("Cargo Fijo"),'LT',0,'C');
            $pdf->Cell(22.,8,("$ $factura->copago_acu"),'LT',0,'C');
            $pdf->Cell(22.9,8,("Cargo Fijo"),'LT',0,'C');
            $pdf->Cell(22.,8,("$ $factura->copago_alc"),'LT',0,'C');
            $pdf->Cell(23,8,("Cargo Fijo"),'LT',0,'C');
            $pdf->Cell(22.,8,("$ $factura->copago_aseo"),'LT',0,'C');
            $pdf->Cell(23,8,("Copago"),'LT',0,'C');
            $pdf->Cell(22.8,8,("$ $factura->copago"),'LTR',0,'C');
           
            
            $pdf->Ln(7);
            $pdf->SetX(17.3);
       
        
            /*----------  Fin Detalles de la tabla  ----------*/
            
            $pdf->Cell(22.7,8,(""),'LT',0,'C');
            $pdf->Cell(22.,8,(""),'LT',0,'C');
            $pdf->Cell(22.9,8,(""),'LT',0,'C');
            $pdf->Cell(22.,8,(""),'LT',0,'C');
            $pdf->Cell(23,8,(""),'LT',0,'C');
            $pdf->Cell(22.,8,(""),'LT',0,'C');
            $pdf->Cell(23,8,("Ajuste Tarifario"),'LT',0,'C');
            $pdf->Cell(22.8,8,("$ $factura->ajuste"),'LTR',0,'C');

         
            $pdf->Ln(7);
            $pdf->SetX(17.3);
       
        
            /*----------  Fin Detalles de la tabla  ----------*/
            
            $pdf->Cell(22.7,8,(""),'LTB',0,'C');
            $pdf->Cell(22.,8,(""),'LTB',0,'C');
            $pdf->Cell(22.9,8,(""),'LTB',0,'C');
            $pdf->Cell(22.,8,(""),'LTB',0,'C');
            $pdf->Cell(23,8,(""),'LTB',0,'C');
            // $pdf->Cell(22.,8,(""),'LTB',0,'C');
            
            //$pdf->SetTextColor(97,97,97);
            $pdf->SetFont('Arial','B',12);
           
            $pdf->Cell(45,8,("Total Pagado"),'LTBR',0,'C');
            $pdf->Cell(22.8,8,("$total"),'LTRB',0,'C');
         
            // $pdf->Ln(10);

            // $pdf->SetTextColor(39,39,51);
            // $pdf->MultiCell(0,9,("-------------------------------------------------------------------------------------------------------------------------------"),0,'C',false);
       

          

            $pdf->Output("I","Factura_Nro_1.pdf",true);


            //echo json_encode($registrado);
        }
    }
    