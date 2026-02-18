(function () {

    const tablaTerceros = document.querySelector('#tablaTerceros');

    if (tablaTerceros) {

        let tablaTercerosData;

        // ============================
        // INIT DATATABLE
        // ============================
        mostrarTerceros();
        verificarCargaTabla();

        function verificarCargaTabla() {
            tablaTercerosData.on('draw', eventosClickEliminar);
        }

        function eventosClickEliminar() {
            const btnEliminar = document.querySelectorAll('#btn_eliminar_tercero');

            btnEliminar.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    leerAccion(e);
                })
            })
        }

        function leerAccion(e) {
            if (e.currentTarget.id === 'btn_eliminar_tercero') {
                eliminarTercero(e);
            }
        }

        // ============================
        // ELIMINAR
        // ============================
        function eliminarTercero(e) {

            const nombre = e.currentTarget
                .parentNode.parentNode.parentNode
                .children[2].textContent;

            const id = e.currentTarget.dataset.terceroId;

            Swal.fire({
                title: `Eliminar tercero ${nombre}?`,
                text: 'Esta accion no se puede deshacer',
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

            const url = `/api/contabilidad/terceros/eliminar?id=${id}`;

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

                tablaTercerosData.ajax.reload();

            } catch (error) {
                console.log(error);
            }
        }

        // ============================
        // DATATABLE
        // ============================
        function mostrarTerceros() {

            $("#tablaTerceros").dataTable().fnDestroy();

            tablaTercerosData = $('#tablaTerceros').DataTable({
                ajax: '/api/terceros',
                deferRender: true,
                retrieve: true,
                processing: true,
                responsive: true
            });
            //   $.ajax({
            //     url:'/api/terceros',
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
