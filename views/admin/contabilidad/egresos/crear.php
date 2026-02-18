<h2 class="dashboard__heading"><?php echo $titulo?></h2>

<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton dashboard__boton--inline" href="/admin/contabilidad/egresos">
        <i class="fa-solid fa-arrow-left"></i>
        volver
    </a>
</div>

<div class="dashboard__formulario">
    <?php include_once __DIR__.'/../../../templates/alertas.php'?>

    <form class="formulario js-egreso-form" method="POST" action="/admin/contabilidad/egresos/crear">

        <?php include_once __DIR__.'/formulario.php'?>

        <input 
            class="formulario__submit formulario__submit--registrar" 
            type="submit" 
            value="Guardar Egreso"
        >
    </form>
</div>
