(function(){
    const infoCaja = document.querySelector('#tablaCajasPagos')
 
    if(infoCaja){
        let tablaCajasPagos;
        const urlActual = new URL(window.location);
        const params = new URLSearchParams(urlActual.search);
        const id = params.get('id');
        const infoCaja = document.querySelector('#tablaCajasPagos')
    
       
        mostrarEstratos();
        function mostrarEstratos(){
            
            if(Number.isInteger(parseInt(id))){
                $("#tablaCajasPagos").dataTable().fnDestroy(); //por si me da error de reinicializar
        
                tablaCajasPagos = $('#tablaCajasPagos').DataTable({
                    ajax:'/api/cajas/pagos?id='+id,
                    "deferRender":true,
                    "retrieve":true,
                    "proccesing":true,
                    responsive:true
                });
            
                $.ajax({
                    url:'/api/cajas/pagos?id='+id,
                    
                    success:function(req){
                        console.log(req)
                    },
                    error:function(error){
                        console.log(error.responseText);
                    }
                    
                })
              
            }else{
                window.location.href='/admin/cajas'
            }
            
        }
    }
})();