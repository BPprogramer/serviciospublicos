<?php 

    namespace Controllers;

    use DateTime;
    use FPDF;
    use Model\Estrato;
    use Model\Factura;
    use Model\Registrado;
    use Model\GeneraAuto;

    require '../vendor/setasign/fpdf/fpdf.php';


/* echo "<pre>";
var_dump($columna);
echo "</pre>"; */

    class ApiFacturas{

        public static function facturas(){
            if(!is_auth()){
                header('Location:/login');
            }
            $fecha = date('Y-m', strtotime('-1 month'));
            $facturas = Factura::fechas($fecha);
        

            $pdf = new FPDF('P','mm','Letter');
            $contador = 0;
            foreach($facturas as $factura){

                if(!$factura->registrado_id) continue;
                $contador = $contador + 1;
                 //calculamos el total a pagar copago - ajuste
                $total = number_format($factura->copago - $factura->ajuste);

                //formatear periodo de facturacion
                $factura->fecha_emision = date("d-m-Y", strtotime($factura->fecha_emision));
                $fechaInicioObj = strtotime($factura->mes_facturado);
                $fechaFinObj = strtotime(date('t-m-Y', $fechaInicioObj));
            
                $factura->periodo_inicio = date('d-m-Y', $fechaInicioObj);
                $factura->periodo_fin = date('d-m-Y', $fechaFinObj);

                //fecha limite
            
                $factura->fecha_limite  = date('d-m-y',strtotime(date('t-m-Y', strtotime($factura->fecha_emision))));
        

    
                
   
                   
                $registrado = Registrado::find($factura->registrado_id);
              
       
                    
               
      
              
                   
            

                $estrato = Estrato::find($registrado->estrato_id);
                mb_internal_encoding('UTF-8');
                $factura->formatearDatosNumber();


           
            
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
                $pdf->Cell(150,9,("Empresa: serviciosPublicos"),0,0,'L');
                $pdf->Ln(5);

                $pdf->SetFont('Arial','',10);
                $pdf->SetTextColor(39,39,51);
                $pdf->Cell(150,9,("RUT: 0000000000"),0,0,'L');

                $pdf->Ln(5);

                $pdf->Cell(150,9,utf8_decode("Dirección San Salvador, El Salvador"),0,0,'L');

                $pdf->Ln(5);

                $pdf->Cell(150,9,utf8_decode("Teléfono: 00000000"),0,0,'L');

                $pdf->Ln(5);


                $pdf->Cell(93,9,utf8_decode("Email: correo@ejemplo.com"),0,0,'L');


                $pdf->Ln(10);

            

                $pdf->SetFont('Arial','',10);
                $pdf->SetTextColor(39,39,51);
                $pdf->Cell(30,7,utf8_decode("Fecha de emisión:"),0,0);
                $pdf->SetTextColor(97,97,97);
                $pdf->Cell(63,7,($factura->fecha_emision),0,0,'L');

                
                $pdf->SetFont('Arial','',15);
                $pdf->SetTextColor(39,39,51);
                $pdf->Cell(29, 6, utf8_decode("Factura No:"), 0, 0);
                $pdf->SetTextColor(255,0,0);
                $pdf->SetFont('Arial','',20);
                $pdf->Cell(64,7,($factura->numero_factura),0,0,'L');

                $pdf->Ln(5);

                $pdf->SetFont('Arial','',10);
                $pdf->SetTextColor(39,39,51);
                $pdf->Cell(31,7,utf8_decode("Periodo Facturado:"),0,0);
                $pdf->SetTextColor(97,97,97);
                $pdf->Cell(116,7,($factura->periodo_inicio.'  -  '.$factura->periodo_fin),0,0,'L');
                $pdf->Ln(5);

                $pdf->SetFont('Arial','',10);
                $pdf->SetTextColor(39,39,51);
                $pdf->Cell(22,7,utf8_decode("Fecha Limite:"),0,0);
                $pdf->SetTextColor(97,97,97);
                $pdf->Cell(116,7,($factura->fecha_limite),0,0,'L');
    

                $pdf->Ln(10);

                $pdf->SetFont('Arial','',10);
                $pdf->SetTextColor(39,39,51);
                $pdf->Cell(13,7,("Cliente:"),0,0);
                $pdf->SetTextColor(97,97,97);
                $pdf->Cell(81,7,utf8_decode("$registrado->nombre $registrado->apellido"),0,0,'L');

                $pdf->SetTextColor(39,39,51);
                $pdf->Cell(20,7,("Nit / Cedula: "),0,0);
                $pdf->SetTextColor(97,97,97);
                $pdf->Cell(73,7,utf8_decode($registrado->cedula_nit),0,0);
            

                $pdf->Ln(5);

                $pdf->SetTextColor(39,39,51);
                $pdf->Cell(13,7,("Celular: "),0,0);
                $pdf->SetTextColor(97,97,97);
                $pdf->Cell(81,7,utf8_decode($registrado->celular),0,0);

                $pdf->SetTextColor(39,39,51);
                $pdf->Cell(11,7,("Barrio: "),0,0);
                $pdf->SetTextColor(97,97,97);
                $pdf->Cell(80,7,utf8_decode($registrado->barrio),0,0);
            
                $pdf->Ln(5);

            
                $pdf->SetTextColor(39,39,51);
                $pdf->Cell(16,7,utf8_decode("Dirección: "),0,0);
                $pdf->SetTextColor(97,97,97);
                $pdf->Cell(78,7,($registrado->direccion),0,0);

                $pdf->SetTextColor(39,39,51);
                $pdf->Cell(13,7,utf8_decode("Código: "),0,0);
                $pdf->SetTextColor(97,97,97);
                $pdf->Cell(80,7,($registrado->codigo_ubicacion),0,0);


                $pdf->Ln(10);
            
                $pdf->SetTextColor(39,39,51);
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(15,7,("Estrato:"),0,0,'L');
    
                $pdf->SetTextColor(97,97,97);
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(80,7,utf8_decode("$estrato->estrato"),0,0);

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
                $pdf->Cell(23,8,("Tarifa Flena"),'LT',0,'C');
                $pdf->Cell(22.8,8,("$ $factura->tarifa_plena"),'LTR',0,'C');
                $pdf->Ln(8);

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
                $pdf->Cell(22.,8,(""),'LTB',0,'C');
                
                //$pdf->SetTextColor(97,97,97);
                $pdf->SetFont('Arial','B',12);
            
                $pdf->Cell(23,8,("Total Pagar"),'LTB',0,'C');
                $pdf->Cell(22.8,8,("$total"),'LTRB',0,'C');
            
                $pdf->Ln(10);

                $pdf->SetTextColor(39,39,51);
                $pdf->MultiCell(0,9,("-------------------------------------------------------------------------------------------------------------------------------"),0,'C',false);
        

                // $pdf->Ln(10);

                $pdf->SetFont('Arial','',10);
                $pdf->SetTextColor(39,39,51);
                $pdf->Cell(30,7,utf8_decode("Fecha de emisión:"),0,0);
                $pdf->SetTextColor(97,97,97);
                $pdf->Cell(63,7,($factura->fecha_emision),0,0,'L');

                
                $pdf->SetFont('Arial','',15);
                $pdf->SetTextColor(39,39,51);
                $pdf->Cell(29, 7, utf8_decode("Factura No:"), 0, 0);
                $pdf->SetTextColor(255,0,0);
                $pdf->SetFont('Arial','',20);
                $pdf->Cell(64,7,($factura->numero_factura),0,0,'L');

                $pdf->Ln(5);

                $pdf->SetFont('Arial','',10);
                $pdf->SetTextColor(39,39,51);
                $pdf->Cell(31,7,utf8_decode("Periodo Facturado:"),0,0);
                $pdf->SetTextColor(97,97,97);
                $pdf->Cell(116,7,($factura->periodo_inicio.'  -  '.$factura->periodo_fin),0,0,'L');
                $pdf->Ln(5);
                $pdf->SetFont('Arial','',10);
                $pdf->SetTextColor(39,39,51);
                $pdf->Cell(22,7,utf8_decode("Fecha Limite:"),0,0);
                $pdf->SetTextColor(97,97,97);
                $pdf->Cell(116,7,($factura->fecha_limite),0,0,'L');

                $pdf->Ln(10);


            
                // $pdf->SetFont('Arial','B',12);
                // $pdf->SetTextColor(255,0,0);
                // $pdf->Cell(35,7,(strtoupper("$factura->numero_factura")),0,0,'C');



                $pdf->SetFont('Arial','',10);
                $pdf->SetTextColor(39,39,51);
                $pdf->Cell(13,7,("Cliente:"),0,0);
                $pdf->SetTextColor(97,97,97);
                $pdf->Cell(81,7,utf8_decode("$registrado->nombre $registrado->apellido"),0,0,'L');

                $pdf->SetTextColor(39,39,51);
                $pdf->Cell(20,7,("Nit / Cedula: "),0,0);
                $pdf->SetTextColor(97,97,97);
                $pdf->Cell(73,7,utf8_decode($registrado->cedula_nit),0,0);
            

                $pdf->Ln(5);

                $pdf->SetTextColor(39,39,51);
                $pdf->Cell(13,7,("Celular: "),0,0);
                $pdf->SetTextColor(97,97,97);
                $pdf->Cell(81,7,utf8_decode($registrado->celular),0,0);

                $pdf->SetTextColor(39,39,51);
                $pdf->Cell(11,7,("Barrio: "),0,0);
                $pdf->SetTextColor(97,97,97);
                $pdf->Cell(80,7,utf8_decode($registrado->barrio),0,0);
            
                $pdf->Ln(5);

            
                $pdf->SetTextColor(39,39,51);
                $pdf->Cell(16,7,utf8_decode("Dirección: "),0,0);
                $pdf->SetTextColor(97,97,97);
                $pdf->Cell(78,7,($registrado->direccion),0,0);

                $pdf->SetTextColor(39,39,51);
                $pdf->Cell(13,7,utf8_decode("Código: "),0,0);
                $pdf->SetTextColor(97,97,97);
                $pdf->Cell(80,7,($registrado->codigo_ubicacion),0,0);


                $pdf->Ln(10);
            
                $pdf->SetTextColor(39,39,51);
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(15,7,("Estrato:"),0,0,'L');
    
                $pdf->SetTextColor(97,97,97);
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(80,7,utf8_decode("$estrato->estrato"),0,0);

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
        

                
                $pdf->Cell(22.7,8,(""),'LTB',0,'C');
                $pdf->Cell(22.,8,(""),'LTB',0,'C');
                $pdf->Cell(22.9,8,(""),'LTB',0,'C');
                $pdf->Cell(22.,8,(""),'LTB',0,'C');
                $pdf->Cell(23,8,(""),'LTB',0,'C');
                $pdf->Cell(22.,8,(""),'LTB',0,'C');

                
                $pdf->SetFont('Arial','B',12);
                $pdf->Cell(23,8,("Total Pagar"),'LTB',0,'C');
                $pdf->Cell(22.8,8,("$total"),'LTRB',0,'C');
            

                // $pdf->SetTextColor(39,39,51);
                // $pdf->MultiCell(0,9,("*** Precios de productos incluyen impuestos. Para poder realizar un reclamo o devolución debe de presentar esta factura ***"),0,'C',false);

                $pdf->Ln(9);

  
              

            }
            $pdf->Output("I","Factura_Nro_1.pdf",true);
            
        }

        public static function generarFacturasManual(){



            $factura = Factura::get(1);
            $fecha_emision =date("Y-m",strtotime($factura->fecha_emision)) ;
            $fecha_actual = date("Y-m");
          
            if($fecha_emision == $fecha_actual){
                echo json_encode(['type'=>'error', 'msg'=>'Las facturas ya han sido generadas con anterioridad']);
                return;
            }
            ob_clean();

    
          
    
            
         
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
               
                $factura->guardar();
                
               
               
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
                if($factura->pagado ==0){
                    $deuda = $factura->copago + $deuda - $factura->ajuste;
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

            //calculamos el total a pagar copago - ajuste
            $total = number_format($factura->copago - $factura->ajuste);

            //formatear periodo de facturacion
            $factura->fecha_emision = date("d-m-Y", strtotime($factura->fecha_emision));
            $fechaInicioObj = strtotime($factura->mes_facturado);
            $fechaFinObj = strtotime(date('t-m-Y', $fechaInicioObj));
        
            $factura->periodo_inicio = date('d-m-Y', $fechaInicioObj);
            $factura->periodo_fin = date('d-m-Y', $fechaFinObj);

            //fecha limite
           
            $factura->fecha_limite = $fechaLimite = date('d-m-y',strtotime(date('t-m-Y', strtotime($factura->fecha_emision))));
      
        

            $registrado = Registrado::find($factura->registrado_id);

            $estrato = Estrato::find($registrado->estrato_id);
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
            $pdf->Cell(150,9,("Empresa: serviciosPublicos"),0,0,'L');
            $pdf->Ln(5);

            $pdf->SetFont('Arial','',10);
            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(150,9,("RUT: 0000000000"),0,0,'L');

            $pdf->Ln(5);

            $pdf->Cell(150,9,utf8_decode("Dirección San Salvador, El Salvador"),0,0,'L');

            $pdf->Ln(5);

            $pdf->Cell(150,9,utf8_decode("Teléfono: 00000000"),0,0,'L');

            $pdf->Ln(5);


            $pdf->Cell(93,9,utf8_decode("Email: correo@ejemplo.com"),0,0,'L');


            $pdf->Ln(10);

          

            $pdf->SetFont('Arial','',10);
            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(30,7,utf8_decode("Fecha de emisión:"),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(63,7,($factura->fecha_emision),0,0,'L');

            
            $pdf->SetFont('Arial','',15);
            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(29, 6, utf8_decode("Factura No:"), 0, 0);
            $pdf->SetTextColor(255,0,0);
            $pdf->SetFont('Arial','',20);
            $pdf->Cell(64,7,($factura->numero_factura),0,0,'L');

            $pdf->Ln(5);

            $pdf->SetFont('Arial','',10);
            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(31,7,utf8_decode("Periodo Facturado:"),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(116,7,($factura->periodo_inicio.'  -  '.$factura->periodo_fin),0,0,'L');
            $pdf->Ln(5);

            $pdf->SetFont('Arial','',10);
            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(22,7,utf8_decode("Fecha Limite:"),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(116,7,($factura->fecha_limite),0,0,'L');
   

            $pdf->Ln(10);

            $pdf->SetFont('Arial','',10);
            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(13,7,("Cliente:"),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(81,7,utf8_decode("$registrado->nombre $registrado->apellido"),0,0,'L');

            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(20,7,("Nit / Cedula: "),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(73,7,utf8_decode($registrado->cedula_nit),0,0);
         

            $pdf->Ln(5);

            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(13,7,("Celular: "),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(81,7,utf8_decode($registrado->celular),0,0);

            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(11,7,("Barrio: "),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(80,7,utf8_decode($registrado->barrio),0,0);
        
            $pdf->Ln(5);

        
            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(16,7,utf8_decode("Dirección: "),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(78,7,($registrado->direccion),0,0);

            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(13,7,utf8_decode("Código: "),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(80,7,($registrado->codigo_ubicacion),0,0);


            $pdf->Ln(10);
          
            $pdf->SetTextColor(39,39,51);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(15,7,("Estrato:"),0,0,'L');
  
            $pdf->SetTextColor(97,97,97);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(80,7,utf8_decode("$estrato->estrato"),0,0);

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
            $pdf->Cell(23,8,("Tarifa Flena"),'LT',0,'C');
            $pdf->Cell(22.8,8,("$ $factura->tarifa_plena"),'LTR',0,'C');
            $pdf->Ln(8);

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
            $pdf->Cell(22.,8,(""),'LTB',0,'C');
            
            //$pdf->SetTextColor(97,97,97);
            $pdf->SetFont('Arial','B',12);
           
            $pdf->Cell(23,8,("Total Pagar"),'LTB',0,'C');
            $pdf->Cell(22.8,8,("$total"),'LTRB',0,'C');
         
            $pdf->Ln(10);

            $pdf->SetTextColor(39,39,51);
            $pdf->MultiCell(0,9,("-------------------------------------------------------------------------------------------------------------------------------"),0,'C',false);
       

            // $pdf->Ln(10);

            $pdf->SetFont('Arial','',10);
            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(30,7,utf8_decode("Fecha de emisión:"),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(63,7,($factura->fecha_emision),0,0,'L');

            
            $pdf->SetFont('Arial','',15);
            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(29, 7, utf8_decode("Factura No:"), 0, 0);
            $pdf->SetTextColor(255,0,0);
            $pdf->SetFont('Arial','',20);
            $pdf->Cell(64,7,($factura->numero_factura),0,0,'L');

            $pdf->Ln(5);

            $pdf->SetFont('Arial','',10);
            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(31,7,utf8_decode("Periodo Facturado:"),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(116,7,($factura->periodo_inicio.'  -  '.$factura->periodo_fin),0,0,'L');
            $pdf->Ln(5);
            $pdf->SetFont('Arial','',10);
            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(22,7,utf8_decode("Fecha Limite:"),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(116,7,($factura->fecha_limite),0,0,'L');

            $pdf->Ln(10);


         
            // $pdf->SetFont('Arial','B',12);
            // $pdf->SetTextColor(255,0,0);
            // $pdf->Cell(35,7,(strtoupper("$factura->numero_factura")),0,0,'C');



            $pdf->SetFont('Arial','',10);
            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(13,7,("Cliente:"),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(81,7,utf8_decode("$registrado->nombre $registrado->apellido"),0,0,'L');

            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(20,7,("Nit / Cedula: "),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(73,7,utf8_decode($registrado->cedula_nit),0,0);
         

            $pdf->Ln(5);

            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(13,7,("Celular: "),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(81,7,utf8_decode($registrado->celular),0,0);

            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(11,7,("Barrio: "),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(80,7,utf8_decode($registrado->barrio),0,0);
        
            $pdf->Ln(5);

        
            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(16,7,utf8_decode("Dirección: "),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(78,7,($registrado->direccion),0,0);

            $pdf->SetTextColor(39,39,51);
            $pdf->Cell(13,7,utf8_decode("Código: "),0,0);
            $pdf->SetTextColor(97,97,97);
            $pdf->Cell(80,7,($registrado->codigo_ubicacion),0,0);


             $pdf->Ln(10);
          
            $pdf->SetTextColor(39,39,51);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(15,7,("Estrato:"),0,0,'L');
  
            $pdf->SetTextColor(97,97,97);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(80,7,utf8_decode("$estrato->estrato"),0,0);

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
       

            
            $pdf->Cell(22.7,8,(""),'LTB',0,'C');
            $pdf->Cell(22.,8,(""),'LTB',0,'C');
            $pdf->Cell(22.9,8,(""),'LTB',0,'C');
            $pdf->Cell(22.,8,(""),'LTB',0,'C');
            $pdf->Cell(23,8,(""),'LTB',0,'C');
            $pdf->Cell(22.,8,(""),'LTB',0,'C');

            
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(23,8,("Total Pagar"),'LTB',0,'C');
            $pdf->Cell(22.8,8,("$total"),'LTRB',0,'C');
         

            // $pdf->SetTextColor(39,39,51);
            // $pdf->MultiCell(0,9,("*** Precios de productos incluyen impuestos. Para poder realizar un reclamo o devolución debe de presentar esta factura ***"),0,'C',false);

            $pdf->Ln(9);


            $pdf->Output("I","Factura_Nro_1.pdf",true);


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
