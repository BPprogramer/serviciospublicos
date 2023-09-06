

(function(){
  
    const contenedorPagado = document.querySelector('#pagos');
    if(contenedorPagado){
            let control = true;
            const urlActual = new URL(window.location);
            const params = new URLSearchParams(urlActual.search);
            const id = params.get('id');
            const nombreCliente = document.querySelector('#cliente')
            const actions = document.querySelector('#actions');
            // actions.addEventListener('click',function(e){
           
            //     if(e.target.id=='btnPagar'){
            //        const swal =  document.querySelector('.swal2-confirm')

            //        swal.addEventListener('click',function(){
            //             setTimeout(()=>{
                         
            //                 consultarPagos();
            //             },2000)
            //        })
            //     }
            // })

           const body  =  document.querySelector('body')
           body.addEventListener('click',function(){
                const inputHidden = document.querySelector('#confirmacionPago')
                if(inputHidden){
                    consultarPagos();
                  
                }
           })

            
           
           
            consultarPagos();
    
            
      
            async function consultarPagos(){
                if(document.querySelector('#confirmacionPago')){
                    document.querySelector('#confirmacionPago').remove()
                }
                
            

                const datos = new FormData();
                datos.append('id',id);
                
                try {
                    //const url = 'http://localhost:3000/api/pagos'//desarrollo 
                    const url = '/api/pagos' //produccion
                    const respuesta = await fetch(url, {
                        method:'POST',
                        body: datos
                    });
                    const pagos = await respuesta.json();
                    
                    if(pagos){
                        
                        imprimirResultados(pagos);
                    }
                                  
                } catch (error) {
                    console.log(error)
                }
            }

            function imprimirResultados(pagos){

                while(contenedorPagado.firstChild){
                    contenedorPagado.removeChild(contenedorPagado.firstChild);
                }
             
                const tabla = document.createElement('TABLE');
                tabla.classList.add('table');

                pagos.forEach(pago => {
  
                    const {id, numero_factura, fecha_pago, periodo_fin, periodo_inicio, monto, numero_pago} = pago
                    const tr = document.createElement('TR');
                    tr.classList.add('table__tr')
                    // tr.dataset.facturaId = id;
                    tr.onclick = function(e){
                        seleccionarPago(e);
                        informacionPago(pago);
                    }

                    const tdNumeroFactura = document.createElement('TD');
                    tdNumeroFactura.classList.add('table__td');
                    tdNumeroFactura.textContent = `#${numero_factura}`

                    const tdFechaPago = document.createElement('TD');
                    tdFechaPago.classList.add('table__td');
                    tdFechaPago.innerHTML = `<span class="table__span">Fecha de Pago:&nbsp; </span>&nbsp; ${fecha_pago}`;

                    const tdPeriodo = document.createElement('TD');
                    tdPeriodo.classList.add('table__td');
                    tdPeriodo.innerHTML = `<span class="table__span">Periodo:&nbsp; </span>&nbsp; ${periodo_inicio} &nbsp; - &nbsp;${periodo_fin}`

                    const tdMonto = document.createElement('TD');
                    tdMonto.classList.add('table__td');
                    tdMonto.innerHTML = ` <span class="table__span">Monto:&nbsp; </span>$ ${monto}`

                    const tdNumeroPago = document.createElement('TD');
                    tdNumeroPago.classList.add('table__td');
                    tdNumeroPago.innerHTML = ` <span class="table__span">Pago #&nbsp; </span> ${numero_pago}`

                  



                    tr.appendChild(tdNumeroFactura)
                    tr.appendChild(tdFechaPago)
                    tr.appendChild(tdPeriodo)
                    tr.appendChild(tdMonto)
                    tr.appendChild(tdNumeroPago)
                    tabla.appendChild(tr);


                });
                
                
                
                
                contenedorPagado.appendChild(tabla)

            }

            function informacionPago(pago){
                const {id, numero_factura,numero_pago, estrato, metodo, periodo_inicio, periodo_fin, monto, recaudador} = pago;
               
                limpiarHtml();
                const acciones = document.querySelector('#payments')
              

                const accionesContenedor = document.createElement('DIV');
                accionesContenedor.classList.add('actions__contenedor')
              
              
                const accionesInfo = document.createElement('DIV');
                accionesInfo.classList.add('actions__info');

                /* cliente */

                // const accionesCliente = document.createElement('DIV');
                // accionesCliente.classList.add('registrado__datos');

                // const clienteLabel = document.createElement('SPAN');
                // clienteLabel.classList.add('registrado__label');
                // clienteLabel.textContent = 'Cliente'
                
                // const clienteDato = document.createElement('P');
                // clienteDato.classList.add('registrado__dato');
                // clienteDato.textContent = nombreCliente.textContent
                
                /* estrato */

                
                
                const accionesRecaudador = document.createElement('DIV');
                accionesRecaudador.classList.add('registrado__datos');

                
                const recaudadorLabel = document.createElement('SPAN');
                recaudadorLabel.classList.add('registrado__label');
                recaudadorLabel.textContent = 'Recaudador'
                
                const recaudadorDato = document.createElement('P');
                recaudadorDato.classList.add('registrado__dato');
                recaudadorDato.textContent = recaudador

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
                montoDato.textContent =`$ ${monto}`;

                /* comprobante */
                const accionesMetodo = document.createElement('DIV');
                accionesMetodo.classList.add('registrado__datos');

                
                const metodoLabel = document.createElement('SPAN');
                metodoLabel.classList.add('registrado__label');
                metodoLabel.textContent = 'Metodo '
                
                const metodoDato = document.createElement('P');
                metodoDato.classList.add('registrado__dato');
                metodoDato.textContent =metodo == 1?'Efectivo':  'Transferencia';
                

                

                // accionesCliente.appendChild(clienteLabel);
                // accionesCliente.appendChild(clienteDato);

                accionesRecaudador.appendChild(recaudadorLabel);
                accionesRecaudador.appendChild(recaudadorDato);
                
                accionesPeriodo.appendChild(periodoLabel);
                accionesPeriodo.appendChild(periodoDato);

                accionesMonto.appendChild(montoLabel);
                accionesMonto.appendChild(montoDato);

                accionesMetodo.appendChild(metodoLabel);
                accionesMetodo.appendChild(metodoDato);
    
                // accionesContenedor.appendChild(accionesCliente);
                accionesContenedor.appendChild(accionesRecaudador);
                accionesContenedor.appendChild(accionesPeriodo);
                accionesContenedor.appendChild(accionesMonto);
                accionesContenedor.appendChild(accionesMetodo);

                acciones.appendChild(accionesContenedor)

                //Botones 
                const botonesContenedor = document.createElement('DIV');
                botonesContenedor.classList.add('actions__botones');

                const btnVerFactura =  document.createElement('SPAN');
                btnVerFactura.classList.add('actions__boton');

                btnVerFactura.onclick = function(){
                    previsualizarComprobante(numero_pago);
                }
                btnVerFactura.textContent = 'Previzualizar';

                botonesContenedor.appendChild(btnVerFactura);

              
                acciones.appendChild(botonesContenedor);

            }

            async function previsualizarComprobante(numero_pago){
               

                const url =`/api/previsualizar-pago?key=${btoa(numero_pago)}`;
                // Abre la URL en una nueva pestaña
                window.open(url, '_blank');

              
            }

           
            async function enviarInformacionPagar(id){
                const url =  'http://localhost:3000/api/pagar';
                const datos = new FormData();
                datos.append('id',id);
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
                        while(contenedorPagado.firstChild){
                            contenedorPagado.removeChild(contenedorPagado.firstChild);
                        }
                       
                     
                      
                        consultarPagos();
                    }
                } catch (error) {
                    
                }
            }

   
          



            function seleccionarPago(e){
                
                const seleccionAnterior =  document.querySelector('.table__seleccion');
                if(seleccionAnterior){
                    seleccionAnterior.classList.remove('table__seleccion')
                }
                const facturaSeleccionada  =e.currentTarget;
                facturaSeleccionada.classList.add('table__seleccion');
            }

   

            function limpiarHtml(){
                const acciones = document.querySelector('#payments')
                while(acciones.firstChild){
                    acciones.removeChild(acciones.firstChild);
                }
            }



     }
    

})();

