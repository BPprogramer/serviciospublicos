(function () {

    const tablaEgresosSimples = document.querySelector('#tablaEgresosSimples');

    if (tablaEgresosSimples) {

        let tablaEgresosSimplesData;

        // ============================
        // INIT DATATABLE
        // ============================
        mostrarEgresosSimples();
        verificarCargaTabla();

        function verificarCargaTabla() {
            tablaEgresosSimplesData.on('draw', eventosClickAnular);
        }

        function eventosClickAnular() {

            const btnAnular = document.querySelectorAll('#btn_anular_egreso_simple');

            btnAnular.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    anularEgresoSimple(e);
                });
            });
        }

        // ============================
        // ANULAR
        // ============================
        function anularEgresoSimple(e) {

            const fila = e.currentTarget
                .parentNode.parentNode.parentNode;

            const fecha = fila.children[1].textContent;
            const id = e.currentTarget.dataset.egresoId;

            // 🔹 PRIMER SWAL
            Swal.fire({
                title: `¿Desea anular el egreso del ${fecha}?`,
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

            const url = `/api/contabilidad/egresos-simples/anular`;

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

                tablaEgresosSimplesData.ajax.reload();

            } catch (error) {
                console.log(error);
            }
        }

        // ============================
        // DATATABLE
        // ============================
        function mostrarEgresosSimples() {

            $("#tablaEgresosSimples").dataTable().fnDestroy();

            tablaEgresosSimplesData = $('#tablaEgresosSimples').DataTable({
                ajax: '/api/contabilidad/egresos-simples',
                deferRender: true,
                retrieve: true,
                processing: true,
                responsive: true
            });
        }
    }

})();
