(function(){
    const tablaPagos = document.querySelector('#tablaPagos');
    if(tablaPagos){

   
        let tablaPagosData;
        mostraroPagos();

        $('#tablaPagos').on('click', '#btn_previsualizar', function(e) {
            // Manejar el evento aquí
       
            previsualizarPago($(this).attr('data-numero-pago'))
        });
        $('#tablaPagos').on('click', '#btn_anular', function(e) {
            // Manejar el evento aquí
            eliminarPagoAlerta($(this).attr('data-numero-pago'));
            // Resto del código
        });

        
      
        
      
        function previsualizarPago(numero_pago){
            const url =`/api/previsualizar-pago?key=${btoa(numero_pago)}`;
                // Abre la URL en una nueva pestaña
                window.open(url, '_blank');

        }
        function eliminarPagoAlerta(numero_pago){
                  
            Swal.fire({
                title: `Esta seguro que desea anular el pago # ${numero_pago}?`,
                text:'Esta accion no se puede deshacer',
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: 'Aceptar',
                denyButtonText: `Cancelar`,
              }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    anularPago(numero_pago);
                    
                  //Swal.fire('Saved!', '', 'success')
                }
              })
        }
        async function anularPago(numero_pago){
            const datos = new FormData();
            datos.append('numero_pago', numero_pago);
            
            
            const url = '/api/anular-pago';
            try {
                const respuesta = await  fetch(url, {
                    body:datos,
                    method:'POST'
                })
                const resultado = await respuesta.json();
                console.log(resultado)
                if(resultado.tipo=='error'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: resultado.mensaje,
        
                    })
                    return;
                }
                Swal.fire({
                    icon: 'success',
                  
                    text: resultado.mensaje,
                    
                })
                tablaPagosData.ajax.reload(); 
            } catch (error) {
                
            }
        }

        function mostraroPagos(){
      
            $("#tablaPagos").dataTable().fnDestroy(); //por si me da error de reinicializar
        
            tablaPagosData = $('#tablaPagos').DataTable({
                ajax: '/api/lista-pagos',
                "deferRender":true,
                "retrieve":true,
                "proccesing":true,
                responsive:true
            });
            // $.ajax({
            //     url:'/api/lista-pagos',
            //     success:function(req){
            //         console.log(req)
            //     },
            //     error:function(error){
            //         console.log(error.responseText);
            //     }
                
            // })
        }
    }
})();


