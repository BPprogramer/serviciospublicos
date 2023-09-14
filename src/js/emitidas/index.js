(function(){
    const emitidasPendientes = document.querySelector('#tablaEmitidasPendientes');
    if(emitidasPendientes){
      
        let tablaEmitidasPendientesData;
        mostrarEmitidasPendientes();
        
        $('#tablaEmitidasPendientes').on('click', '#btn_previsualizar', function(e) {
            // Manejar el evento aquí
       
            previsualizarFactura($(this).attr('data-numero-factura'))
        });
        
      function previsualizarFactura(numero_factura){
        const url =`/api/previsualizar-factura?key=${btoa(numero_factura)}`;
        // Abre la URL en una nueva pestaña
        window.open(url, '_blank');
      }

        function mostrarEmitidasPendientes(){
      
            $("#tablaEmitidasPendientes").dataTable().fnDestroy(); //por si me da error de reinicializar
    
            tablaEmitidasPendientesData = $('#tablaEmitidasPendientes').DataTable({
                ajax: '/api/emitidas',
                "deferRender":true,
                "retrieve":true,
                "proccesing":true,
                responsive:true
            });
            // $.ajax({
            //     url:'/api/emitidas',
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