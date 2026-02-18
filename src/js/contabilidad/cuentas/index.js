(function () {

    const tablaCuentas = document.querySelector('#tablaCuentas');

    if (tablaCuentas) {

        let tablaCuentasData;

        // ============================
        // INIT DATATABLE
        // ============================
        mostrarCuentas();
        verificarCargaTabla();

        function verificarCargaTabla() {
            tablaCuentasData.on('draw', eventosClickEliminar);
        }

        function eventosClickEliminar() {
            const btnEliminar = document.querySelectorAll('#btn_eliminar_cuenta');

            btnEliminar.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    leerAccion(e);
                })
            })
        }

        function leerAccion(e) {
            if (e.currentTarget.id === 'btn_eliminar_cuenta') {
                eliminarCuenta(e);
            }
        }

        // ============================
        // ELIMINAR
        // ============================
        function eliminarCuenta(e) {

            const nombre = e.currentTarget
                .parentNode.parentNode.parentNode
                .children[2].textContent;

            const id = e.currentTarget.dataset.cuentaId;

            Swal.fire({
                title: `Eliminar cuenta ${nombre}?`,
                text: 'Esta acción no se puede deshacer',
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: 'Aceptar',
                denyButtonText: `Cancelar`,
            }).then((result) => {
                if (result.isConfirmed) {
                    enviarInformacionEliminar(id);
                }
            });
        }

        async function enviarInformacionEliminar(id) {

            const url = `/api/contabilidad/cuentas/eliminar?id=${id}`;

            try {
                const respuesta = await fetch(url);
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

                tablaCuentasData.ajax.reload();

            } catch (error) {
                console.log(error);
            }
        }

        // ============================
        // DATATABLE
        // ============================
        function mostrarCuentas() {

            $("#tablaCuentas").dataTable().fnDestroy();

            tablaCuentasData = $('#tablaCuentas').DataTable({
                ajax: '/api/contabilidad/cuentas',
                deferRender: true,
                retrieve: true,
                processing: true,
                responsive: true
            });
        }
    }

})();
