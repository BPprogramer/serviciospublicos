<h2 class="dashboard__heading"><?php echo $titulo?></h2>

<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton dashboard__boton--inline" href="/admin/contabilidad/egresos/crear">
        <i class="fa-solid fa-circle-plus"></i>
        Añadir Egreso
    </a>
</div>

<div class="dashboard__contenedor" id="contenedorEgresos">

    <table class="display responsive table" id="tablaEgresos">
        <thead>
            <tr>
                <th>#</th>
                <th>Consecutivo</th>
                <th>Fecha</th>
                <th>Tercero</th>
                <th>Cuenta Bancaria</th>
                <th>Total Débito</th>
                <th>Total Crédito</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
    </table>

</div>
