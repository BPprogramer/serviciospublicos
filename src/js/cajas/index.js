
(function(){
    const tablaCajas = document.querySelector('#tablaCajas');
    if(tablaCajas){
        

        let tablaCajasData;

        $('#tablaCajas').on('click', '#estadoCaja', function(e) {
            // Manejar el evento aquí
            const id = $(this).data('cajaId');
            alertaCierre(id);
        });
       

        mostrarEstratos();
        verificarCargaTabla();
        function verificarCargaTabla(){
        
            tablaCajasData.on('draw', function(){
                const estadoCaja = document.querySelector('#estadoCaja');
           
                estadoCaja.addEventListener('dblclick',function(e){
                   
                    const id = e.target.dataset.cajaId;
                    alertaCierre(id);
                })
            });
        }

        function alertaCierre(id){
            Swal.fire({
                
                title: 'Esta Seguro de Cerrar esta Caja?',
                text:'Esta acción no se puede deshacer',
                icon:'warning',
                showDenyButton: true,
          
                confirmButtonText: 'Seguro',
                denyButtonText: `Cancelar`,
              }).then((result) => {
                

                if (result.isConfirmed) {
                 
                   cerrarCaja(id);
                } 
              })
        }

        async function cerrarCaja(id){
           
            const datos = new FormData();
            datos.append('id', id);
   
            try {
                const url = '/api/cajas/cerrar';
                //const url =`${location.origin}/api/cajas/cerrar`;
                //const url = 'http://localhost:3000/api/cajas/cerrar';
                const respuesta = await fetch(url,{
                    body:datos,
                    method:'POST'
                });
                const resultado = await respuesta.json();
                
                if(resultado.tipo == 'success'){
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
                    tablaCajasData.ajax.reload(); 
                }
            } catch (error) {
                console.log(error)
            }
        }
        function mostrarEstratos(){
            $("#tablaCajas").dataTable().fnDestroy(); //por si me da error de reinicializar
        
            tablaCajasData = $('#tablaCajas').DataTable({
                ajax: '/servicios/api/cajas',
                "deferRender":true,
                "retrieve":true,
                "proccesing":true,
                responsive:true
            });
            // $.ajax({
            //     url:'/api/cajas',
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


