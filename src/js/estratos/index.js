
(function(){


    const tablaEstratos = document.querySelector('#tablaEstratos');

    if(tablaEstratos){

        $('#tablaEstratos').on('click', '#btn_eliminar_estrato', function(e) {
            // Manejar el evento aquí
      
            eliminarEstrato(e);
        });

        let tablaEstratosData;
        mostrarEstratos();
        verificarCargaTabla();
        function verificarCargaTabla(){
      
            tablaEstratosData.on('draw', eventosClick);
        }

        function eventosClick(){

            const btnsInfo = document.querySelectorAll('#btn_info_estrato');
            
            btnsInfo.forEach(btnInfo=>{
                btnInfo.addEventListener('click', function(e){
                  
                    infoEstrato(e);
                })
            })
          
            const btnEliminar = document.querySelectorAll('#btn_eliminar_estrato');
            btnEliminar.forEach(btnEliminar=>{
              
                btnEliminar.addEventListener('click', function(e){
                   // console.log(e.currentTarget)
             
                    eliminarEstrato(e);
                })
            })
        }


      

        async function infoEstrato(e){
            const id = e.currentTarget.dataset.estratoId;
            const url = `/servicios/api/estratos/info?id=${id}`;
  
            try {
                const respuesta = await fetch(url);
                const resultado = await respuesta.json();
                
                if(resultado.tipo == 'error'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: resultado.mensaje,
        
                    })
                    return;
                }
                mostrarInfo(resultado);
            } catch (error) {
                console.log(error)
            }
            
        }

        function mostrarInfo(resultado){
  

            const modal = document.createElement('DIV');
            modal.classList.add('modal');
            modal.innerHTML = `
                <div class="modal__informacion" id="info">
                    <h3>Información del Estrato </h3>
                    
                    <ul class="modal__lista">
                         <li class="modal__elemento modal__elemento--grid">
                           
                            <p class="modal__info">Estrato: <span>${resultado.estrato}</span></p>
                            <p class="modal__info">Año Vigente: <span>${resultado.year}</span></p>
                            <p class="modal__info">Tarifa Plena: <span>${parseFloat((resultado.tarifa_plena)).toLocaleString('en')}</span></p>
                            <p class="modal__info">Número de Facturas Vencidas: <span>${resultado.facturas_vencidas}</span></p>
        
                         </li>
                   

                         <li class="modal__elemento modal__elemento--grid">
                            <p class="modal__info">Porcentaje Subsidio: <span>${resultado.porcentaje_subsidio}%</span></p>
                            <p class="modal__info">Porcentaje Copago: <span>${resultado.porcentaje_copago}%</span></p>
                            <p class="modal__info">Subsidio: <span>${parseFloat((resultado.subsidio)).toLocaleString('en')}</span></p>
                            <p class="modal__info">Copago: <span>${parseFloat((resultado.copago)).toLocaleString('en')}</span></p>
                         </li>
                        
                         <li class="modal__elemento modal__elemento--grid">
                            <p class="modal__info">Porcentaje Acueducto: <span>${parseFloat((resultado.porcentaje_acu)).toLocaleString('en')}%</span></p>
                            <p class="modal__info">Tarifa Plena Acueducto: <span>${parseFloat((resultado.tarifa_plena_acu)).toLocaleString('en')}</span></p>
                            <p class="modal__info">Valor Subsidio Acueducto: <span>${parseFloat((resultado.subsidio_acu)).toLocaleString('en')}</span></p>
                            <p class="modal__info">Valor Copago Acueducto : <span>${parseFloat((resultado.copago_acu)).toLocaleString('en')}</span></p>
                         </li>
                         
                         <li class="modal__elemento modal__elemento--grid">
                            <p class="modal__info">Porcentaje Alcantarillado: <span>${parseFloat((resultado.porcentaje_alc)).toLocaleString('en')}%</span></p>
                            <p class="modal__info">Tarifa Plena Alcantarillado: <span>${parseFloat((resultado.tarifa_plena_alc)).toLocaleString('en')}</span></p>
                            <p class="modal__info">Valor Subsidio Alcantarillado: <span>${parseFloat((resultado.subsidio_alc)).toLocaleString('en')}</span></p>
                            <p class="modal__info">Valor Copago Alcantarillado : <span>${parseFloat((resultado.copago_alc)).toLocaleString('en')}</span></p>
                         </li>
                         
                         <li class="modal__elemento modal__elemento--grid">
                            <p class="modal__info">Porcentaje Aseo: <span>${parseFloat((resultado.porcentaje_aseo)).toLocaleString('en')}%</span></p>
                            <p class="modal__info">Tarifa Plena Aseo: <span>${parseFloat((resultado.tarifa_plena_aseo)).toLocaleString('en')}</span></p>
                            <p class="modal__info">Valor Subsidio Aseo: <span>${parseFloat((resultado.subsidio_aseo)).toLocaleString('en')}</span></p>
                            <p class="modal__info">Valor Copago Aseo : <span>${parseFloat((resultado.copago_aseo)).toLocaleString('en')}</span></p>
                         </li>
                         <li class="modal__elemento modal__elemento--grid">
                            <p class="modal__info">Porcentaje Ajuste: <span>${parseFloat((resultado.porcentaje_ajuste)).toLocaleString('en')}%</span></p>
                            <p class="modal__info">Tarifa Plena Ajuste: <span>${parseFloat((resultado.ajuste)).toLocaleString('en')}</span></p>
                           
                         </li>
                         
                        
                    </ul>

                    <div class="modal__opciones">
                      
             
                      
                        <button type="button" class="modal__cerrar-modal">Cerrar</button>
                    </div>
                    
                </div>
            `
            setTimeout(()=>{
                const info = document.querySelector('#info');
                info.classList.add('modal__informacion--animar');
            },10)
            modal.addEventListener('click',function(e){
                e.preventDefault();
                if(e.target.classList.contains('modal__cerrar-modal')){
                    const info = document.querySelector('#info');
                    info.classList.add('modal__informacion--cerrar');
                    setTimeout(()=>{
                        modal.remove();
                        
                       
                    },500)
                }
               
           
            })

            document.querySelector('.dashboard__contenedor').appendChild(modal);
        }

        function eliminarEstrato(e){
   
            const estrato =e.currentTarget.parentNode.parentNode.parentNode.children[1].textContent;
            
          
            const id = e.currentTarget.dataset.estratoId;
            Swal.fire({
                title: `Esta seguro que desea eliminar el estrato ${estrato}?`,
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
      
            url = `/servicios/api/estratos/eliminar?id=${id}`;
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
                tablaEstratosData.ajax.reload(); 
            } catch (error) {
                console.log(error)
            }
        }


 
        function mostrarEstratos(){
      
            $("#tablaEstratos").dataTable().fnDestroy(); //por si me da error de reinicializar
    
            tablaEstratosData = $('#tablaEstratos').DataTable({
                ajax: '/servicios/api/estratos',
                "deferRender":true,
                "retrieve":true,
                "proccesing":true,
                responsive:true
            });
            // $.ajax({
            //     url:'/api/estratos',
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
