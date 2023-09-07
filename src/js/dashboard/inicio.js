(function(){
    const caja = document.querySelector('#cajas');
    if(caja){
        const subscriptores = document.querySelector('#subscriptores');
        const subscriptoresVigentes = document.querySelector('#subscriptoresVigentes');
        const subscriptoresInactivos = document.querySelector('#subscriptoresInactivos');
        const pagosVigentes = document.querySelector('#pagosVigentes');
        const fecha = document.querySelector('#fecha');
        const ingreso = document.querySelector('#ingreso');
        const totalPagos = document.querySelector('#totalPagos');

        informacionUsuario();
        fechaActual();
        fecha.addEventListener('change',function(e){
            const fechaSeleccionada = e.target.value;
            consultarIngresosFecha(fechaSeleccionada)
        })

        async function informacionUsuario(){
            try {
                const respuesta = await fetch('/api/inicio/registrados');
                const resultado = await respuesta.json();
                mostrarResultados(resultado);
            } catch (error) {
                
            }
        }

        function mostrarResultados(resultado){
            const {registrados, registrados_activos, registrados_inactivos, pagos_vigentes, ingresos} = resultado;
            subscriptores.textContent = registrados;
            subscriptoresVigentes.textContent = registrados_activos;
            subscriptoresInactivos.textContent = registrados_inactivos;
            pagosVigentes.textContent = pagos_vigentes;
            totalPagos.textContent = '$'+ingresos

        }
        

        function fechaActual(){
            const fechaActual = new Date().toISOString().slice(0, 10);
            fecha.value = fechaActual;
            consultarIngresosFecha(fechaActual);
        }
        async function consultarIngresosFecha(fecha){
            const datos = new FormData();
            datos.append('fecha', fecha);
       
            try {
                const respuesta = await fetch('/api/inicio/fecha', {
                    body:datos,
                    method:'POST'
                });
                const resultado = await respuesta.json();
                mostrarIngresos(resultado);
            } catch (error) {
                
            }
        }
        function mostrarIngresos(resultado){
            ingreso.textContent = '$'+resultado
        }
        
    }

})();