<h2 class="dashboard__heading"><?php echo $titulo?></h2>

<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton dashboard__boton--inline" href="/admin/contabilidad/bancos/crear">
        <i class="fa-solid fa-circle-plus"></i>
        Añadir Banco
    </a>
</div>

<div class="dashboard__contenedor" id="contenedorBancos">

    <table class="display responsive table" id="tablaBancos">
        <thead>
            <tr>
                <th>#</th>    
                <th>Nombre</th>
                <th>Código</th>
                <th>NIT</th>
                <th>DV</th>
                <th>Acciones</th>
            </tr>
        </thead>
    </table>

</div>

<script>
</script>
