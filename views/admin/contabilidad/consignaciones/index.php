<h2 class="dashboard__heading"><?php echo $titulo ?></h2>

<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton dashboard__boton--inline" href="/admin/contabilidad/consignaciones/crear">
        <i class="fa-solid fa-circle-plus"></i>
        Añadir Consignación
    </a>
</div>

<div class="dashboard__contenedor" id="contenedorConsignaciones">

    <table class="display responsive table" id="tablaConsignaciones">
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Cuenta Bancaria</th>
                <th>Tipo</th>

                <th>Descripción</th>
                <th>Responsable</th>
                <th>Estado</th>
                <th>Monto</th>
                <th>Acciones</th>
            </tr>
        </thead>
    </table>

</div>

<script src="/build/js/consignaciones.js"></script>