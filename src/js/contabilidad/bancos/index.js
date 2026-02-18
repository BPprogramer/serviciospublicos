(function () {

    const tablaBancos = document.querySelector('#tablaBancos');

    if (tablaBancos) {

        let tablaBancosData;

        // ============================
        // INIT DATATABLE
        // ============================
        mostrarBancos();
        verificarCargaTabla();

        function verificarCargaTabla() {
            tablaBancosData.on('draw', eventosClickEliminar);
        }

        function eventosClickEliminar() {
            const btnEliminar = document.querySelectorAll('#btn_eliminar_banco');

            btnEliminar.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    leerAccion(e);
                })
            })
        }

        function leerAccion(e) {
            if (e.currentTarget.id === 'btn_eliminar_banco') {
                eliminarBanco(e);
            }
        }

        // ============================
        // ELIMINAR
        // ============================
        function eliminarBanco(e) {

            const nombre = e.currentTarget
                .parentNode.parentNode.parentNode
                .children[1].textContent;

            const id = e.currentTarget.dataset.bancoId;

            Swal.fire({
                title: `Eliminar banco ${nombre}?`,
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

            const url = `/api/contabilidad/bancos/eliminar?id=${id}`;

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

                tablaBancosData.ajax.reload();

            } catch (error) {
                console.log(error);
            }
        }

        // ============================
        // DATATABLE
        // ============================
        function mostrarBancos() {

            $("#tablaBancos").dataTable().fnDestroy();

            tablaBancosData = $('#tablaBancos').DataTable({
                ajax: '/api/contabilidad/bancos',
                deferRender: true,
                retrieve: true,
                processing: true,
                responsive: true
            });
        }
    }

})();
