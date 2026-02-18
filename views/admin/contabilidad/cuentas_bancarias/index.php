<h2 class="dashboard__heading"><?php echo $titulo?></h2>

<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton dashboard__boton--inline" href="/admin/contabilidad/cuentas-bancarias/crear">
        <i class="fa-solid fa-circle-plus"></i>
        Añadir Cuenta Bancaria
    </a>
</div>

<div class="dashboard__contenedor" id="contenedorCuentasBancarias">

    <table class="display responsive table" id="tablaCuentasBancarias">
        <thead>
            <tr>
                <th>#</th>
                <th>Banco</th>
                <th>Número Cuenta</th>
                <th>Nombre</th>
                <th>Saldo Inicial</th>
                <th>Acciones</th>
            </tr>
        </thead>
    </table>

</div>

<script>
</script>
