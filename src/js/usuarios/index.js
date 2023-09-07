
(function(){
   

    
    const tablaUsuarios = document.querySelector('#tablaUsuarios');
    if(tablaUsuarios){
        let tablaUsuariosData;
        tablaUsuarios.addEventListener('click',function(e){
            leerAccion(e);
        })

        $('#tablaUsuarios').on('click', '#btn_eliminar_usuario', function(e) {
            // Manejar el evento aquí
      
            eliminarUsuario(e);
        });
    
        

        mostrarUsuarios();

        verificarCargaTabla();
        function verificarCargaTabla(){
      
            tablaUsuariosData.on('draw', eventosClick);
        }

        function eventosClick(){

            
          
            const btnEliminar = document.querySelectorAll('#btn_eliminar_usuario');
            btnEliminar.forEach(btnEliminar=>{
              
                btnEliminar.addEventListener('click', function(e){
                   // console.log(e.currentTarget)
                    
                   eliminarUsuario(e);
                })
            })
        }


        function leerAccion(e){
        
            // if(e.target.id=='btn_eliminar_usuario'){
            //     eliminarUsuario(e);
            // }
            if(e.target.id=='btn_cambiar_estado'){
                editarEstado(e);
            }
        }
        function eliminarUsuario(e){
            const nombre = e.currentTarget.parentNode.parentNode.parentNode.children[1].textContent;
            const id = e.currentTarget.dataset.usuarioId;
            Swal.fire({
                title: `Esta seguro que desea eliminar a  ${nombre}?`,
                text:'Esta accion no se puede deshacer',
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: 'Aceptar',
                denyButtonText: `Cancelar`,
              }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    enviarInformacionEliminar(id);
                    
                  //Swal.fire('Saved!', '', 'success')
                }
              })
        }
        async function enviarInformacionEliminar(id){
           
            url = `/api/usuarios/eliminar?id=${id}`;
            try {
                const respuesta = await fetch(url)
                const resultado = await respuesta.json();
              
             
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
                tablaUsuariosData.ajax.reload(); 
            } catch (error) {
                console.log(error)
            }
        }
        function editarEstado(e){
           
            const estadoActual = e.target.dataset.estadoActual;
            const nombre = e.target.parentNode.parentNode.children[1].textContent;
            const id = e.target.dataset.usuarioId;

            let mensajeAlerta = '';
        
            if(estadoActual == 1){
                mensajeAlerta = 'Esta seguro que desea desactivar a';
            }else{
                mensajeAlerta = 'Esta seguro que desea activar a';
            }
        
            
            Swal.fire({
                title: `${mensajeAlerta}  ${nombre}?`,
                text:'Esta accion no se puede deshacer',
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: 'Aceptar',
                denyButtonText: `Cancelar`,
              }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    enviarInformacionEstado(id);
                    
                  //Swal.fire('Saved!', '', 'success')
                }
              })
        }

        async function enviarInformacionEstado(id){
            url = `/api/usuarios/editar-estado?id=${id}`;
            try {
                const respuesta = await fetch(url)
                const resultado = await respuesta.json();
           
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
                  
                    title: resultado.mensaje,
                    
                })
                tablaUsuariosData.ajax.reload(); 
            } catch (error) {
                console.log(error)
            }
        }

        function mostrarUsuarios(){
            $("#tablaUsuarios").dataTable().fnDestroy(); //por si me da error de reinicializar
    
            tablaUsuariosData = $('#tablaUsuarios').DataTable({
                ajax: '/api/usuarios',
                "deferRender":true,
                "retrieve":true,
                "proccesing":true,
                responsive:true
            });
        }
       
    

    }  
        
   

 
 


  
})();