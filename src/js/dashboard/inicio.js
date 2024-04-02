
        
(function(){
    document.addEventListener("DOMContentLoaded", function(){
        const caja = document.querySelector('#cajas');
    if(caja){

        const subscriptores = document.querySelector('#subscriptores');
        const subscriptoresVigentes = document.querySelector('#subscriptoresVigentes');
        const subscriptoresInactivos = document.querySelector('#subscriptoresInactivos');
        const pagosVigentes = document.querySelector('#pagosVigentes');
        const fecha = document.querySelector('#fecha');
        const ingreso = document.querySelector('#ingreso');
        const ingresosMensuales = document.querySelector('#ingresosMensuales');
        const totalPagos = document.querySelector('#totalPagos');
        const totalConsignaciones = document.querySelector('#totalConsignaciones');
        const selectYear = document.querySelector('#year');
        const selectMes = document.querySelector('#mes');

        //selects para la consignacion
        const selectYearConsignacion = document.querySelector('#year_consignacion')
        const selectMesConsignacion = document.querySelector('#mes_consignacion')


        const consignacionAseo = document.querySelector('#consignacionAseo')
        const consignacionAlc = document.querySelector('#consignacionAlc')
        const consignacionAcu = document.querySelector('#consignacionAcu')
        const total_del_mes = document.querySelector('#total_del_mes')
        const ya_consignado = document.querySelector('#ya_consignado')
        const a_consginar = document.querySelector('#a_consignar')

        const contenedor_estratos = document.querySelector('#estratos');
        const btnImprimirFacturasFiltradas = document.querySelector('#btnImprimirFacturasFiltradas');
     


        

        selectYear.addEventListener('change',leerInputs);
        selectMes.addEventListener('change',leerInputs);

        selectYearConsignacion.addEventListener('change',leerInputsConsignacion);
        selectMesConsignacion.addEventListener('change',leerInputsConsignacion);



        informacionUsuario();
        fechaActual();
        leerInputs();
        consultarEstratos();

       

        btnImprimirFacturasFiltradas.addEventListener('click',function(e){
            const estratoId = contenedor_estratos.value;
            if(estratoId==0){
                Swal.fire({
                    icon: 'error',
        
                    text: 'Porfavor seleccione un estrato'
                })
            }else{
                const url = `/api/facturas?estratos-key=${btoa(estratoId)}`
                //const url = `${location.origin}/api/facturas`
                window.open(url, '_blank');
            }
        })

        fecha.addEventListener('change',function(e){
            const fechaSeleccionada = e.target.value;
            consultarIngresosFecha(fechaSeleccionada)
        })
     
        async function consultarEstratos(){
            const url = `${location.origin}/api/estratos-all`;
            try {
                const respuesta = await fetch(url);
                const resultado = await respuesta.json();
                mostrarEstratos(resultado);
            } catch (error) {
                console.log(error)
            }
        }

        function mostrarEstratos(estratos){
     
            estratos.forEach(estrato => {
                const option = document.createElement('OPTION');
                option.value=estrato.id
                option.textContent = estrato.estrato
                contenedor_estratos.appendChild(option)
            });
        }

        function leerInputs(){
            const year = selectYear.value;
            const mes = selectMes.value;
            const fecha = year+"-"+mes;
            consultarIngresosMensuales(fecha);
        }
        function leerInputsConsignacion(){
            const year = selectYearConsignacion.value;
            const mes = selectMesConsignacion.value;
            const fecha = year+"-"+mes;
      
       
            consultarConsignaciones(fecha);
        }

        async function consultarIngresosMensuales(fecha){
            const datos = new FormData();
            datos.append('fecha', fecha);
            const url = '/api/inicio/ingresos-mensuales';
            try {
                const respuesta = await fetch(url, {
                    body:datos,
                    method:'POST'
                });
                const resultado = await respuesta.json();
                ingresosMensuales.textContent = '$'+resultado;

            } catch (error) {
                
            }
        }
        async function consultarConsignaciones(fecha){
        
            const datos = new FormData();
            datos.append('fecha', fecha);
            const url = '/api/inicio/consignaciones';
            try {
                const respuesta = await fetch(url, {
                    body:datos,
                    method:'POST'
                });
                const resultado = await respuesta.json();
        
                imprimirDatosConsignacion(resultado);

            } catch (error) {
                
            }
        }
        function imprimirDatosConsignacion(resultado){
      
            consignacionAseo.textContent = '';
            consignacionAcu.textContent = '';
            consignacionAlc.textContent = '';
            total_del_mes.textContent = '';
            ya_consignado.textContent = '';

            a_consginar.textContent = '';
         
        
            consignacionAseo.textContent = '$'+(resultado.aseo||0);
            consignacionAcu.textContent = '$'+(resultado.acueducto || 0);
            consignacionAlc.textContent = '$'+(resultado.alcantarillado || 0);
            total_del_mes.textContent = '$'+(resultado.total_del_mes || 0);
            ya_consignado.textContent = '$'+(resultado.ya_consignado || 0);
            a_consginar.textContent = '$'+(resultado.a_consignar || 0);
        }

        async function informacionUsuario(){
            try {
                const respuesta = await fetch('/api/inicio/registrados');
                const resultado = await respuesta.json();
                mostrarResultados(resultado);
            } catch (error) {
                
            }
        }

        function mostrarResultados(resultado){
            
            const {registrados, registrados_activos, registrados_inactivos, pagos_vigentes, ingresos, consignaciones} = resultado;
            subscriptores.textContent = registrados;
            subscriptoresVigentes.textContent = registrados_activos;
            subscriptoresInactivos.textContent = registrados_inactivos;
            pagosVigentes.textContent = pagos_vigentes;
            totalPagos.textContent = '$'+ingresos
            totalConsignaciones.textContent = '$'+consignaciones

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

        let data = [
            {
              sheet: "Adults",
              columns: [
                { label: "User", value: "user" }, // Top level data
                { label: "Age", value: (row) => row.age + " years" }, // Custom format
                { label: "Phone", value: (row) => (row.more ? row.more.phone || "" : "") }, // Run functions
              ],
              content: [
                { user: "Andrea", age: 20, more: { phone: "11111111" } },
                { user: "Luis", age: 21, more: { phone: "12345678" } },
              ],
            },
            {
              sheet: "Children",
              columns: [
                { label: "User", value: "user" }, // Top level data
                { label: "Age", value: "age", format: '# "years"' }, // Column format
                { label: "Phone", value: "user.more.phone", format: "(###) ###-####" }, // Deep props and column format
              ],
              content: [
                { user: "Manuel", age: 16, more: { phone: 9999999900 } },
                { user: "Ana", age: 17, more: { phone: 8765432135 } },
              ],
            },
          ]
          
          let settings = {
            fileName: "MySpreadsheet", // Name of the resulting spreadsheet
            extraLength: 3, // A bigger number means that columns will be wider
            writeOptions: {}, // Style options from https://github.com/SheetJS/sheetjs#writing-options
          }
          
      


    }



    })
    

})();