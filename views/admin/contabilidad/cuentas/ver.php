<h3 class="dashboard__heading dashboard__heading--subscriptor">
    <span>Cuenta: </span> <?php echo $titulo?>
</h3>

<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton dashboard__boton--inline" href="/admin/contabilidad/cuentas">
        <i class="fa-solid fa-arrow-left"></i>
        Volver
    </a>
</div>

<div class="flexbox">

    <section class="dashboard__contenedor">
        <div class="registrado">

            <h4 class="registrado__nombre">
                Información de la Cuenta
                &nbsp;&nbsp;
                <a class="registrado__accion" href="/admin/contabilidad/cuentas/editar?id=<?php echo $cuenta->id?>">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
            </h4>

            <div class="registrado__contenedor">

                <div class="registrado__datos">
                    <span class="registrado__label">Código</span>
                    <p class="registrado__dato"><?php echo $cuenta->codigo?></p>
                </div>

                <div class="registrado__datos">
                    <span class="registrado__label">Nombre</span>
                    <p class="registrado__dato"><?php echo $cuenta->nombre?></p>
                </div>

                <div class="registrado__datos">
                    <span class="registrado__label">Tipo</span>
                    <p class="registrado__dato"><?php echo ucfirst($cuenta->tipo)?></p>
                </div>

            </div>

        </div>
    </section>

</div>
