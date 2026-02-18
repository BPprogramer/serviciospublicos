(function () {

    const movimientos = document.querySelector('#movimientosCuenta');
    if (!movimientos) return;

    const cuentaId = movimientos.dataset.cuentaId;

    const inputFechaInicio = document.querySelector('#fecha_inicio');
    const inputFechaFin = document.querySelector('#fecha_fin');
    const btnFiltrar = document.querySelector('#btnFiltrarMovimientos');

    // =========================
    // TABS
    // =========================

    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(btn => {
        btn.addEventListener('click', function () {

            tabButtons.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));

            this.classList.add('active');
            document.getElementById(this.dataset.tab).classList.add('active');

        });
    });

    // =========================
    // DATA TABLES
    // =========================

    const tablaConsignaciones = $('#tablaConsignacionesMov').DataTable({
        ajax: {
            url: '/api/contabilidad/movimientos/consignaciones',
            data: function (d) {
                d.cuenta_id = cuentaId;
                d.fecha_inicio = inputFechaInicio.value;
                d.fecha_fin = inputFechaFin.value;
            }
        },
        processing: true,
        responsive: true,
        destroy: true
    });

    const tablaEgresos = $('#tablaEgresosMov').DataTable({
        ajax: {
            url: '/api/contabilidad/movimientos/egresos',
            data: function (d) {
                d.cuenta_id = cuentaId;
                d.fecha_inicio = inputFechaInicio.value;
                d.fecha_fin = inputFechaFin.value;
            }
        },
        processing: true,
        responsive: true,
        destroy: true
    });

    const tablaEgresosSimples = $('#tablaEgresosSimplesMov').DataTable({
        ajax: {
            url: '/api/contabilidad/movimientos/egresos-simples',
            data: function (d) {
                d.cuenta_id = cuentaId;
                d.fecha_inicio = inputFechaInicio.value;
                d.fecha_fin = inputFechaFin.value;
            }
        },
        processing: true,
        responsive: true,
        destroy: true
    });

    // =========================
    // RECARGAR AL FILTRAR
    // =========================

    btnFiltrar.addEventListener('click', function () {

        tablaConsignaciones.ajax.reload();
        tablaEgresos.ajax.reload();
        tablaEgresosSimples.ajax.reload();

    });

})();
