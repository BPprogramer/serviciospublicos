(function () {

    const tablaConsignaciones = document.querySelector('#tablaConsignaciones');

    if (tablaConsignaciones) {

        let tablaConsignacionesData;

        // ============================
        // INIT DATATABLE
        // ============================
        mostrarConsignaciones();
        verificarCargaTabla();

        function verificarCargaTabla() {
            tablaConsignacionesData.on('draw', eventosClickAnular);
        }

        function eventosClickAnular() {

            const btnAnular = document.querySelectorAll('#btn_anular_consignacion');

            btnAnular.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    anularConsignacion(e);
                });
            });
        }

        // ============================
        // ANULAR
        // ============================
        function anularConsignacion(e) {

            const fila = e.currentTarget
                .parentNode.parentNode.parentNode;

            const fecha = fila.children[1].textContent;
            const id = e.currentTarget.dataset.consignacionId;

            // 🔹 PRIMER SWAL
            Swal.fire({
                title: `¿Desea anular la consignación del ${fecha}?`,
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Proceder',
                cancelButtonText: 'Cancelar'
            }).then((result) => {

                if (result.isConfirmed) {

                    // 🔹 SEGUNDO SWAL (RAZÓN OBLIGATORIA)
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

            const url = `/api/contabilidad/consignaciones/anular`;

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

                tablaConsignacionesData.ajax.reload();

            } catch (error) {
                console.log(error);
            }
        }

        // ============================
        // DATATABLE
        // ============================
        function mostrarConsignaciones() {

            $("#tablaConsignaciones").dataTable().fnDestroy();

            tablaConsignacionesData = $('#tablaConsignaciones').DataTable({
                ajax: '/api/contabilidad/consignaciones',
                deferRender: true,
                retrieve: true,
                processing: true,
                responsive: true
            });
        }
    }

})();
