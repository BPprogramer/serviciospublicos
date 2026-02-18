<h2 class="dashboard__heading"><?php echo $titulo ?></h2>

<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton dashboard__boton--inline" href="/admin/contabilidad/egresos-simples/crear">
        <i class="fa-solid fa-circle-plus"></i>
        Añadir Egreso Simple
    </a>
</div>

<div class="dashboard__contenedor" id="contenedorEgresosSimples">

    <table class="display responsive table" id="tablaEgresosSimples">
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Cuenta Bancaria</th>
                <th>Monto</th>
                <th>Descripción</th>
                <th>Responsable</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
    </table>

</div>

<script src="/build/js/egresos-simples.js"></script>
