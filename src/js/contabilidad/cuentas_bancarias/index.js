(function () {

    const tablaCuentasBancarias = document.querySelector('#tablaCuentasBancarias');

    if (tablaCuentasBancarias) {

        let tablaCuentasBancariasData;

        // ============================
        // INIT DATATABLE
        // ============================
        mostrarCuentasBancarias();
        verificarCargaTabla();

        function verificarCargaTabla() {
            tablaCuentasBancariasData.on('draw', eventosClickEliminar);
        }

        function eventosClickEliminar() {
            const btnEliminar = document.querySelectorAll('#btn_eliminar_cuenta_bancaria');

            btnEliminar.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    leerAccion(e);
                });
            });
        }

        function leerAccion(e) {
            if (e.currentTarget.id === 'btn_eliminar_cuenta_bancaria') {
                eliminarCuentaBancaria(e);
            }
        }

        // ============================
        // ELIMINAR
        // ============================
        function eliminarCuentaBancaria(e) {

            const numeroCuenta = e.currentTarget
                .parentNode.parentNode.parentNode
                .children[2].textContent;

            const id = e.currentTarget.dataset.cuentaId;

            Swal.fire({
                title: `Eliminar cuenta bancaria ${numeroCuenta}?`,
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

            const url = `/api/contabilidad/cuentas_bancarias/eliminar?id=${id}`;

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

                tablaCuentasBancariasData.ajax.reload();

            } catch (error) {
                console.log(error);
            }
        }

        // ============================
        // DATATABLE
        // ============================
        function mostrarCuentasBancarias() {

            $("#tablaCuentasBancarias").dataTable().fnDestroy();

            tablaCuentasBancariasData = $('#tablaCuentasBancarias').DataTable({
                ajax: '/api/contabilidad/cuentas-bancarias',
                deferRender: true,
                retrieve: true,
                processing: true,
                responsive: true
            });
        }
    }

})();
