

(function(){
    
    const btnImprimirFacturas = document.querySelector('#btnImprimirFacturas');
    if(btnImprimirFacturas){
        const contenedorSwitch = document.querySelector('#switch');
        const btnGenerarFacturas = document.querySelector('#btnGenerarFacturas');
     
        const btnEliminarFacturas  = document.querySelector('#btnEliminarFacturas');

        btnEliminarFacturas.addEventListener('click',alertaEliminarFacturas)
      

        revisarGeneraAuto();

        function alertaEliminarFacturas(){
            Swal.fire({
                title: 'Esta seguro de generar las facturas de este mes?',
              
             
                showDenyButton: true,
                confirmButtonText: 'Eliminar',
                denyButtonText: `Cancelar`,
              }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    eliminarFacturas();
                }
              })
        }

        async function eliminarFacturas(){
            try {
                const respuesta = await fetch('/api/eliminar-facturas');
                const resultado = await respuesta.json();
             
                Swal.fire({
                    icon: resultado.type,
        
                    text: resultado.msg
                })
            } catch (error) {
                
            }
        }

        btnImprimirFacturas.addEventListener('click',function(){
            const url = '/api/facturas'
            //const url = `${location.origin}/api/facturas`
            window.open(url, '_blank');
        })

        function alertaGenerarFacturasAuto(){
         
            Swal.fire({
                title: 'Esta seguro de generar las facturas de este mes?',
              
             
                showDenyButton: true,
                confirmButtonText: 'Generar',
                denyButtonText: `Cancelar`,
              }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                     generarFacturas();
                }
              })
        }

        async function generarFacturas(){
            btnGenerarFacturas.removeEventListener('click', alertaGenerarFacturasAuto)
            btnGenerarFacturas.classList.add('inicio__boton-generar--inactivo');
              
            try {
                const respuesta = await fetch(`/api/generar-facturas-manual`);
    
                const resultado = await respuesta.json()
                console.log(resultado)
                btnGenerarFacturas.classList.remove('inicio__boton-generar--inactivo');
                btnGenerarFacturas.addEventListener('click', alertaGenerarFacturasAuto)
                    Swal.fire({
                        icon: resultado.type,
            
                        text: resultado.msg
                    })
     
               
            } catch (error) {
                console.log(error)
            }
        }

        async function cambiarEstadoAuto(estado){
            let auto;
            if(estado){
                auto = 1;
            }else{
                auto = 0;
            }



      
            const datos = new FormData();
            datos.append('id',1)
            datos.append('auto',auto);

            const url = `/api/generar-auto`;
           
            try {
                await fetch(url,{
                    body:datos,
                    method:'POST'
                })
          
             
                revisarGeneraAuto()
            
            } catch (error) {
                
            }
        }

        async function revisarGeneraAuto(){


         
            try {
                const respuesta = await fetch('/api/generar-auto');
               
                const resultado = await respuesta.json()
               
                const auto = resultado.auto;
                generarCheckBox(auto);
               
            } catch (error) {
                console.log(error)
            }

    
        }

        function generarCheckBox(auto){
            const checkbox = document.createElement('INPUT')
            checkbox.type="checkbox"
            checkbox.id = "checkbox";
        
            if(btnGenerarFacturas.classList.contains('inicio__boton-generar--inactivo')){
                btnGenerarFacturas.classList.remove('inicio__boton-generar--inactivo')
            }
            if(auto==1){
                checkbox.checked = true
                btnGenerarFacturas.classList.add('inicio__boton-generar--inactivo');
                btnGenerarFacturas.removeEventListener('click', alertaGenerarFacturasAuto)

                
            }else{
                checkbox.checked = false
                btnGenerarFacturas.addEventListener('click', alertaGenerarFacturasAuto)
            
            }
            checkbox.onchange = function(){
                setTimeout(()=>{
                    cambiarEstadoAuto(checkbox.checked)
                },300)
                
            }
            
            const slider = document.createElement('DIV')
 
            slider.classList.add('slider', 'round');
            contenedorSwitch.appendChild(checkbox)
            contenedorSwitch.appendChild(slider)

        }

    }

})();



