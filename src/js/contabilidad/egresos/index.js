(function () {

    const tablaEgresos = document.querySelector('#tablaEgresos');

    if (tablaEgresos) {

        let tablaEgresosData;

        // ============================
        // INIT DATATABLE
        // ============================
        mostrarEgresos();
        verificarCargaTabla();

        function verificarCargaTabla() {
            tablaEgresosData.on('draw', eventosClickEliminar);
        }

        function eventosClickEliminar() {
            const btnEliminar = document.querySelectorAll('#btn_eliminar_egreso');

            btnEliminar.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    leerAccion(e);
                });
            });
        }

        function leerAccion(e) {
            if (e.currentTarget.id === 'btn_eliminar_egreso') {
                eliminarEgreso(e);
            }
        }

        // ============================
        // ELIMINAR
        // ============================
        function eliminarEgreso(e) {

            const fila = e.currentTarget
                .parentNode.parentNode.parentNode;

            const consecutivo = fila.children[1].textContent;
            const id = e.currentTarget.dataset.egresoId;

            // 🔹 PRIMER SWAL (Confirmación)
            Swal.fire({
                title: `¿Desea anular el egreso ${consecutivo}?`,
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Proceder',
                cancelButtonText: 'Cancelar'
            }).then((result) => {

                if (result.isConfirmed) {

                    // 🔹 SEGUNDO SWAL (Razón obligatoria)
                    Swal.fire({
                        title: 'Razón de anulación',
                        input: 'textarea',
                        inputLabel: 'Ingrese la razón',
                        inputPlaceholder: 'Escriba la razón...',
                        showCancelButton: true,
                        confirmButtonText: 'Anular',
                        cancelButtonText: 'Cancelar',
                        inputValidator: (value) => {
                            if (!value) {
                                return 'La razón de anulación es obligatoria';
                            }
                        }
                    }).then((resultRazon) => {
                    
                        if (resultRazon.isConfirmed) {
                            enviarAnulacion(id, resultRazon.value);
                        }

                    });

                }

            });
        }


        async function enviarAnulacion(id, razon) {

            const url = `/api/contabilidad/egresos/anular`;

            try {


                const respuesta = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: id,
                        razon_anulacion: razon
                    })
                });

             
                  

                const resultado = await respuesta.json();
         
                if (resultado.tipo === 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: resultado.mensaje,
                    });
                    return;
                }

                Swal.fire({
                    icon: 'success',
                    text: resultado.mensaje,
                });

                tablaEgresosData.ajax.reload();

            } catch (error) {
                console.log(error);
            }
        }


        // ============================
        // DATATABLE
        // ============================
        function mostrarEgresos() {

            $("#tablaEgresos").dataTable().fnDestroy();

            tablaEgresosData = $('#tablaEgresos').DataTable({
                ajax: '/api/contabilidad/egresos',
                deferRender: true,
                retrieve: true,
                processing: true,
                responsive: true
            });
        }
    }

})();
