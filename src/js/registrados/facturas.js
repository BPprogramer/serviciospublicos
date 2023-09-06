

(function(){
    const contenedorFactura = document.querySelector('#facturas');
    if(contenedorFactura){
            const urlActual = new URL(window.location);
            const params = new URLSearchParams(urlActual.search);
            const id = params.get('id');
            const nombreCliente = document.querySelector('#cliente')
           
            consultarFacturas();

            async function consultarFacturas(){

                const datos = new FormData();
                datos.append('id',id);
                
                try {
                    const url = '/api/facturas-registrado'
                    const respuesta = await fetch(url, {
                        method:'POST',
                        body: datos
                    });
                    const resultado = await respuesta.json();
                    if(resultado){
                  
                        imprimirDeuda(resultado.deuda);
                        imprimirResultados(resultado.facturas);
                    }
                                  
                } catch (error) {
                    console.log(error)
                }
            }
            function imprimirDeuda(valorDeuda){
                const deuda = document.querySelector('#deuda')
                deuda.classList = [];
                deuda.classList.add('deuda__valor')
                if(valorDeuda==0){
                    
                    deuda.classList.add('deuda__valor--pagado')
                }else{
                    deuda.classList.add('deuda__valor--vencido')
                }
                deuda.textContent = `$ ${valorDeuda.toLocaleString('en')}`
            }

            function imprimirResultados(facturas){
                

                
                const tabla = document.createElement('TABLE');
                tabla.classList.add('table');

                facturas.forEach(factura => {
  
                    const {id, numero_factura, fecha_emision, periodo_fin, periodo_inicio, copago, pagado, ajuste} = factura
                    const dateFechaActual = new Date();
                    const mesActual = dateFechaActual.getMonth()+1;
                 
                    const arregloFecha = fecha_emision.split('-');
                    const mesEmision = parseFloat(arregloFecha[1])
                    
                   
                   const diferenciaFechas = mesActual - mesEmision;
                   
                   
                
              
                    const tr = document.createElement('TR');
                    tr.classList.add('table__tr')
                    // tr.dataset.facturaId = id;
                    tr.onclick = function(e){
                        seleccionFactura(e);
                        informacionFactura(factura);
                    }

                    const tdNumeroFactura = document.createElement('TD');
                    tdNumeroFactura.classList.add('table__td');
                    tdNumeroFactura.textContent = `#${numero_factura}`

                    const tdEmitida = document.createElement('TD');
                    tdEmitida.classList.add('table__td');
                    tdEmitida.innerHTML = `<span class="table__span">Emitida:&nbsp; </span>&nbsp; ${fecha_emision}`;

                    const tdPeriodo = document.createElement('TD');
                    tdPeriodo.classList.add('table__td');
                    tdPeriodo.innerHTML = `<span class="table__span">Periodo:&nbsp; </span>&nbsp; ${periodo_inicio} &nbsp; - &nbsp;${periodo_fin}`

                    const tdMonto = document.createElement('TD');
                    tdMonto.classList.add('table__td');
                    tdMonto.innerHTML = ` <span class="table__span">Monto:&nbsp; </span>$ ${parseFloat((copago-ajuste)).toLocaleString('en')}`

                    const tdEstado = document.createElement('TD');
                    tdEstado.classList.add('table__td');
                    if(diferenciaFechas>0 && pagado==0){
                        tdEstado.innerHTML = ` <span class="table__boton table__boton--vencida">Impaga</span>`
                    }else{
                        tdEstado.innerHTML = ` <span class="table__boton table__boton--${ pagado== 1?'pagado':'impaga'}">${pagado == 1?'Pagado':'Impaga'}</span>`
                    
                    }   
                     
                   



                    tr.appendChild(tdNumeroFactura)
                    tr.appendChild(tdEmitida)
                    tr.appendChild(tdPeriodo)
                    tr.appendChild(tdMonto)
                    tr.appendChild(tdEstado)
                    tabla.appendChild(tr);


                });
                
                
                
                
                contenedorFactura.appendChild(tabla)

            }

            function informacionFactura(factura){
                const {id, numero_factura, fecha_emision, estrato, periodo_inicio, periodo_fin, copago, pagado} = factura;
               
           
                limpiarHtml();
                const acciones = document.querySelector('#actions')
              

                const accionesContenedor = document.createElement('DIV');
                accionesContenedor.classList.add('actions__contenedor')
              
              
                const accionesInfo = document.createElement('DIV');
                accionesInfo.classList.add('actions__info');

                /* cliente */

                const accionesCliente = document.createElement('DIV');
                accionesCliente.classList.add('registrado__datos');

                const clienteLabel = document.createElement('SPAN');
                clienteLabel.classList.add('registrado__label');
                clienteLabel.textContent = 'Cliente'
                
                const clienteDato = document.createElement('P');
                clienteDato.classList.add('registrado__dato');
                clienteDato.textContent = nombreCliente.textContent
                
                /* estrato */

                
                
                const accionesEstrato = document.createElement('DIV');
                accionesEstrato.classList.add('registrado__datos');

                
                const estratoLabel = document.createElement('SPAN');
                estratoLabel.classList.add('registrado__label');
                estratoLabel.textContent = 'Estrato'
                
                const estratoDato = document.createElement('P');
                estratoDato.classList.add('registrado__dato');
                estratoDato.textContent = estrato

                /* Periodo */

                const accionesPeriodo = document.createElement('DIV');
                accionesPeriodo.classList.add('registrado__datos');

                
                const periodoLabel = document.createElement('SPAN');
                periodoLabel.classList.add('registrado__label');
                periodoLabel.textContent = 'Periodo'
                
                const periodoDato = document.createElement('P');
                periodoDato.classList.add('registrado__dato');
                
                periodoDato.innerHTML = `${periodo_inicio} &nbsp;- &nbsp;  ${periodo_fin}`

                /* Monto */

                const accionesMonto = document.createElement('DIV');
                accionesMonto.classList.add('registrado__datos');

                
                const montoLabel = document.createElement('SPAN');
                montoLabel.classList.add('registrado__label');
                montoLabel.textContent = 'Monto '
                
                const montoDato = document.createElement('P');
                montoDato.classList.add('registrado__dato');
                montoDato.textContent =`$ ${(parseFloat(copago)).toLocaleString('en')}`;
                

                

                accionesCliente.appendChild(clienteLabel);
                accionesCliente.appendChild(clienteDato);

                accionesEstrato.appendChild(estratoLabel);
                accionesEstrato.appendChild(estratoDato);
                
                accionesPeriodo.appendChild(periodoLabel);
                accionesPeriodo.appendChild(periodoDato);

                accionesMonto.appendChild(montoLabel);
                accionesMonto.appendChild(montoDato);
    
                accionesContenedor.appendChild(accionesCliente);
                accionesContenedor.appendChild(accionesEstrato);
                accionesContenedor.appendChild(accionesPeriodo);
                accionesContenedor.appendChild(accionesMonto);

                acciones.appendChild(accionesContenedor)

                //Botones 
                const botonesContenedor = document.createElement('DIV');
                botonesContenedor.classList.add('actions__botones');

                const btnVerFactura =  document.createElement('SPAN');
                btnVerFactura.classList.add('actions__boton');

                btnVerFactura.onclick = function(){
                    previsualizarFactura(numero_factura);
                }
                btnVerFactura.textContent = 'Previzualizar';

               

                if(pagado==0){
                    const btnPagar =  document.createElement('SPAN');
                    btnPagar.classList.add('actions__boton');
                    btnPagar.textContent = 'Pago Efectivo';
                    btnPagar.id = 'btnPagar';
                   
                    const btnBanco =  document.createElement('SPAN');
                    btnBanco.classList.add('actions__boton');
                    btnBanco.textContent = 'transferencia';
                    btnBanco.id = 'btnBanco';

                    btnPagar.onclick = function(){
                        btnBanco.style.display = 'none'
                        btnPagar.style.display = 'none'
                        pagarFactura(factura, 1, btnPagar,btnBanco);
                    }
                    btnBanco.onclick = function(){
                        btnBanco.style.display = 'none'
                        btnPagar.style.display = 'none'
                        pagarFactura(factura, 0, btnPagar,btnBanco);
                    }
    
                 
                    botonesContenedor.appendChild(btnPagar);
                    botonesContenedor.appendChild(btnBanco);
                }

                botonesContenedor.appendChild(btnVerFactura);

                acciones.appendChild(botonesContenedor);

            }

            async function previsualizarFactura(numero_factura){
               

                const url =`/api/previsualizar-factura?key=${btoa(numero_factura)}`;
                // Abre la URL en una nueva pestaña
                window.open(url, '_blank');

              
            }

            function pagarFactura(factura, metodo, btnPagar,btnBanco){
                const {id,copago, periodo_inicio, periodo_fin, numero_factura}  = factura;
   

                const registrado = document.createElement('DIV');
                registrado.classList.add('registrado');

                const metodoDatos = document.createElement('DIV');
                metodoDatos.classList.add('registrado__datos');

                const metodoLabel = document.createElement('SPAN');
                metodoLabel.classList.add('registrado__label');
                metodoLabel.textContent = 'Metodo de Pago:'

                const metodoDato = document.createElement('P');
                metodoDato.classList.add('registrado__dato')
                metodoDato.textContent = metodo==1?'Efectivo':'Transferencia';

                const registradoDatos = document.createElement('DIV');
                registradoDatos.classList.add('registrado__datos');

                const registradoLabel = document.createElement('SPAN');
                registradoLabel.classList.add('registrado__label');
                registradoLabel.textContent = 'Cliente:'

                const registradoDato = document.createElement('P');
                registradoDato.classList.add('registrado__dato')
                registradoDato.textContent = nombreCliente.textContent;

                const periodoDatos = document.createElement('DIV');
                periodoDatos.classList.add('registrado__datos');

                
                const periodoLabel = document.createElement('SPAN');
                periodoLabel.classList.add('registrado__label');
                periodoLabel.textContent = 'Periodo:'
                
                const periodoDato = document.createElement('P');
                periodoDato.classList.add('registrado__dato');
                periodoDato.innerHTML = `${periodo_inicio} &nbsp;- &nbsp;  ${periodo_fin}`

                /* Monto */

                const montoDatos = document.createElement('DIV');
                montoDatos.classList.add('registrado__datos');

                
                const montoLabel = document.createElement('SPAN');
                montoLabel.classList.add('registrado__label');
                montoLabel.textContent = 'Monto: '
                
                const montoDato = document.createElement('P');
                montoDato.classList.add('registrado__dato');
                montoDato.textContent =`$ ${(parseFloat(copago)).toLocaleString('en')}`;
                

                

                

                metodoDatos.appendChild(metodoLabel);
                metodoDatos.appendChild(metodoDato);

                registradoDatos.appendChild(registradoLabel);
                registradoDatos.appendChild(registradoDato);
            
                periodoDatos.appendChild(periodoLabel)
                periodoDatos.appendChild(periodoDato)

                montoDatos.appendChild(montoLabel)
                montoDatos.appendChild(montoDato)

                registrado.appendChild(metodoDatos);
                registrado.appendChild(registradoDatos);
                registrado.appendChild(periodoDatos);
                registrado.appendChild(montoDatos);

               
  

                Swal.fire({
                    title: `Confirme La información`,
                    html: registrado,
                    text:'Esta accion no se puede deshacer',
                    icon: 'question',
                    showDenyButton: true,
                    confirmButtonText: 'Aceptar',
                    denyButtonText: `Cancelar`,
                  }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        enviarInformacionPagar(id, metodo, btnPagar, btnBanco);
                        
                      //Swal.fire('Saved!', '', 'success')
                    }else{
                        btnPagar.style.display = 'block'
                        btnBanco.style.display  = 'block'
                    }
                  })
            }

            async function enviarInformacionPagar(id, metodo, btnPagar, btnBanco){
                //const url =  'http://localhost:3000/api/pagar'; //desarrollo
                const url =  '/api/pagar';
                const datos = new FormData();
                datos.append('id',id);
                datos.append('metodo', metodo);
                try {
                    const respuesta = await fetch(url,{
                        body:datos,
                        method:'POST'
                    })
                    const resultado = await respuesta.json();
            
               
                 
                    if(resultado.tipo == 'error'){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops',
                            text: resultado.mensaje
                          })
                    }else{
                        Swal.fire({
                            icon: 'success',
                            
                            text: resultado.mensaje
                          })
                        const body =  document.querySelector('body');
                        const inputHidden = document.createElement("input");
                        inputHidden.type = "hidden"; 
                        inputHidden.id = "confirmacionPago"; 
                        body.appendChild(inputHidden);


                        while(contenedorFactura.firstChild){
                            contenedorFactura.removeChild(contenedorFactura.firstChild);
                        }
                     
                        document.querySelector('#btnPagar').remove();
                        consultarFacturas();
                     
                    }
                } catch (error) {
                    
                }
            }

  
          



            function seleccionFactura(e){

                const seleccionAnterior =  document.querySelector('.table__seleccion');
                if(seleccionAnterior){
                    seleccionAnterior.classList.remove('table__seleccion')
                }
                const facturaSeleccionada  =e.currentTarget;
                facturaSeleccionada.classList.add('table__seleccion');
            }

   

            function limpiarHtml(){
                const acciones = document.querySelector('#actions')
                while(acciones.firstChild){
                    acciones.removeChild(acciones.firstChild);
                }
            }
            
       

       
        

     }

})();