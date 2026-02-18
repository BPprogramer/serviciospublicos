<h3 class="dashboard__heading dashboard__heading--subscriptor">
    <span>Banco: </span> <?php echo $titulo ?>
</h3>

<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton dashboard__boton--inline" href="/admin/contabilidad/bancos">
        <i class="fa-solid fa-arrow-left"></i>
        Volver
    </a>
</div>




<div class="flexbox">

    <section class="dashboard__contenedor">
        <div class="registrado">

            <h4 class="registrado__nombre">
                Información del Banco
                &nbsp;&nbsp;
                <a class="registrado__accion" href="/admin/contabilidad/bancos/editar?id=<?php echo $banco->id ?>">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
            </h4>

            <div class="registrado__contenedor">

                <div class="registrado__datos">
                    <span class="registrado__label">Nombre</span>
                    <p class="registrado__dato"><?php echo $banco->nombre ?></p>
                </div>

                <div class="registrado__datos">
                    <span class="registrado__label">Código</span>
                    <p class="registrado__dato"><?php echo $banco->codigo ?: '-' ?></p>
                </div>

                <div class="registrado__datos">
                    <span class="registrado__label">NIT</span>
                    <p class="registrado__dato"><?php echo $banco->nit ?: '-' ?></p>
                </div>

                <div class="registrado__datos">
                    <span class="registrado__label">DV</span>
                    <p class="registrado__dato"><?php echo $banco->dv ?: '-' ?></p>
                </div>

            </div>

        </div>
    </section>

</div>