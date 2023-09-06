<h2 class="dashboard__heading"><?php echo $titulo?></h2>
<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton" href="/servicios/admin/registrados">
        <i class="fa-solid fa-arrow-left"></i>
        volver
    </a>
</div>

<div class="dashboard__formulario">
    <?php include_once __DIR__.'/../../templates/alertas.php'?>
    <form   class="formulario" method="POST" action="/servicios/admin/registrados/crear" id="formulario_crear">

        <?php include_once __DIR__.'/formulario.php'?>

        <input class="formulario__submit formulario__submit--registrar" type="submit" value="Registrar Subscriptor">
    </form>
</div>