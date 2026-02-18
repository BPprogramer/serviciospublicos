<h2 class="dashboard__heading"><?php echo $titulo?></h2>

<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton dashboard__boton--inline" href="/admin/contabilidad/cuentas/crear">
        <i class="fa-solid fa-circle-plus"></i>
        Añadir Cuenta
    </a>
</div>

<div class="dashboard__contenedor" id="contenedorCuentas">

    <table class="display responsive table" id="tablaCuentas">
        <thead>
            <tr>
                <th>#</th>    
                <th>Código</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Acciones</th>
            </tr>
        </thead>
    </table>

</div>

<script>
</script>
