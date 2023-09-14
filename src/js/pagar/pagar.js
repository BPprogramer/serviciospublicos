

(function(){
    const contenedorPagar  = document.querySelector('#contenedorPagar');
    if(contenedorPagar){
        const inputCodigo = document.querySelector('#numero_factura');
        const contenedorFacturas = document.querySelector('#pagarFacturas');
        const guardarPagosBtn = document.querySelector('#guardarPagos');
        const totalFacturas = document.querySelector('#totalFacturas');
        const totalRecaudo = document.querySelector('#totalRecaudo');

        let facturas;
        let arreglofacturas = []
        consultarFacturas()
        guardarPagosBtn.addEventListener('click', confirmarPagos);

        async function guardarPagos(){
            const datos = new FormData();
        
            const arregloPagos = arreglofacturas.map(factura => factura.id);
          
            datos.append('pagos', JSON.stringify(arregloPagos));
            try {
                const respuesta = await fetch('/api/subir-pagos', {
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
                    limpiarHtml();
                    totalFacturas.textContent = 0;
                    totalRecaudo.textContent = '$0'
                    arreglofacturas = [];
                    Swal.fire({
                        icon: 'success',
                        
                        text: resultado.mensaje
                      })
                  
                }
            } catch (error) {
                
            }
        }

       function confirmarPagos(){
            if(arreglofacturas.length>0){
                Swal.fire({
                    title: `Esta seguro que desea subir ${ totalFacturas.textContent} Pagos por ${ totalRecaudo.textContent}`,
                    icon: 'question',
                    showDenyButton: true,
                    confirmButtonText: 'Aceptar',
                    denyButtonText: `Cancelar`,
                  }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        guardarPagos();
                        
                      //Swal.fire('Saved!', '', 'success')
                    }
                  })
            }else{
                Swal.fire(
                    'Error',
                    'Debe agregar al menos una factura',
                    'error'
                )
            }
       }


        function infoFactura(facturaEncontrada){
      
            if(arreglofacturas.find(factura => factura.id=== facturaEncontrada.id)){
                setTimeout(()=>{
                    Swal.fire(
                        'Error',
                        'La Factura ya fue agregada a la fila de pagos',
                        'error'
                      )
                    return;
                },1)
               
            }else   if(facturaEncontrada.pagado == 1){
                setTimeout(()=>{
                Swal.fire(
                    'Error',
                    'La Factura ya ha sido cancelada',
                    'error'
                  )
                return;
            },1)
                    
            }else   if(facturaEncontrada.combinado == 1){
                setTimeout(()=>{
                Swal.fire(
                    'Error',
                    'La Factura ha sido combinada',
                    'error'
                )
                return;
            },1)
            }else{
                Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Factura agregada a la pila',
                showConfirmButton: false,
                timer: 1500
            })
             
                const {id, numero_factura, registrado_nombre, estrato, mes_facturado,copago,ajuste,saldo_anterior } = facturaEncontrada;
                arreglofacturas.unshift({
                    numero_factura:numero_factura,
                    subscriptor:registrado_nombre,
                    estrato:estrato,
                    mes_facturado:mes_facturado,
                    copago:copago,
                    ajuste:ajuste,
                    saldo_anterior:saldo_anterior,
                    id:id
                
    
                })
    
                const tabla = document.createElement('TABLE');
                tabla.classList.add('table');
            
                limpiarHtml();
                let recaudo = 0;
                let cantidadFacturas = 0;
                arreglofacturas.forEach(factura=>{
              
                    const { numero_factura, subscriptor, estrato, mes_facturado,copago, ajuste, saldo_anterior} = factura;
            
                    const tr = document.createElement('TR');
                    tr.classList.add('table__tr')
                    const tdNumeroFactura = document.createElement('TD');
                    tdNumeroFactura.classList.add('table__td');
                    tdNumeroFactura.textContent = `#${numero_factura}`
    
                    const tdRegistrado = document.createElement('TD');
                    tdRegistrado.classList.add('table__td');
                    tdRegistrado.innerHTML = `<span class="table__span">Cliente:&nbsp; </span>&nbsp; ${subscriptor}`;
    
                    const tdEstrato = document.createElement('TD');
                    tdEstrato.classList.add('table__td');
                    tdEstrato.innerHTML = `<span class="table__span">Estrato:&nbsp; </span>&nbsp; ${estrato}`
    
                    const tdMesFacturado = document.createElement('TD');
                    tdMesFacturado.classList.add('table__td');
                    tdMesFacturado.innerHTML = `<span class="table__span">Mes Facturado:&nbsp; </span>&nbsp; ${mes_facturado}`
    
                    let monto = parseFloat(copago)+ parseFloat(saldo_anterior)- parseFloat(ajuste)
                  
                    const tdCopago = document.createElement('TD');
                    tdCopago.classList.add('table__td');
                    tdCopago.innerHTML = ` <span class="table__span">Monto:&nbsp; </span>$ ${monto.toLocaleString('en')}`
    
                    
                   
    
    
    
                    tr.appendChild(tdNumeroFactura)
                    tr.appendChild(tdRegistrado)
                    tr.appendChild(tdEstrato)
                    tr.appendChild(tdMesFacturado)
                    tr.appendChild(tdCopago)
                    tabla.appendChild(tr);
                    recaudo = recaudo + monto;
                    cantidadFacturas = cantidadFacturas +1;

                })

                totalFacturas.textContent = cantidadFacturas
                totalRecaudo.textContent = '$'+recaudo.toLocaleString('en')
    
                contenedorFacturas.appendChild(tabla)

            }
          
          
           
            
        }



        function consultarFactura(e){
            const numero_factura = e.target.value
           
         
            const facturaEncontrada = facturas.find(factura => factura.numero_factura === numero_factura);
            if (facturaEncontrada) {
                inputCodigo.value='';
                infoFactura(facturaEncontrada);
            } 
        }
        
        function agregarEventos(){
         
            inputCodigo.addEventListener('input',function(e){
             
            
                consultarFactura(e);
            })
        }
        


        async function consultarFacturas(){
            try {
                const resultado = await fetch('/api/facturas-por-pagar');
                const respuesta = await resultado.json();
                facturas = respuesta;
             
              
                agregarEventos();
            } catch (error) {
                
            }
        }
       
        function limpiarHtml(){
            while(contenedorFacturas.firstChild){
                contenedorFacturas.removeChild(contenedorFacturas.firstChild)
            }
        }

    
    }


})();