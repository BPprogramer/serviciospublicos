<h3 class="dashboard__heading dashboard__heading--subscriptor">
    <span>Tercero: </span> <?php echo $titulo?>
</h3>

<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton dashboard__boton--inline" href="/admin/contabilidad/terceros">
        <i class="fa-solid fa-arrow-left"></i>
        Volver
    </a>
</div>

<div class="flexbox">

    <!-- INFORMACION GENERAL -->
    <section class="dashboard__contenedor">
        <div class="registrado">

            <h4 class="registrado__nombre">
                Información General
                &nbsp;&nbsp;
                <a class="registrado__accion" href="/admin/contabilidad/terceros/editar?id=<?php echo $tercero->id?>">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
            </h4>

            <div class="registrado__contenedor">

                <div class="registrado__datos">
                    <span class="registrado__label">Tipo Persona</span>
                    <p class="registrado__dato"><?php echo ucfirst($tercero->tipo_persona)?></p>
                </div>

                <div class="registrado__datos">
                    <span class="registrado__label">Nombre / Razón Social</span>
                    <p class="registrado__dato"><?php echo $tercero->nombre?></p>
                </div>

                <div class="registrado__datos">
                    <span class="registrado__label">Documento</span>
                    <p class="registrado__dato">
                        <?php echo $tercero->documento?>
                        <?php if($tercero->dv): ?>
                            - <?php echo $tercero->dv?>
                        <?php endif; ?>
                    </p>
                </div>

                <div class="registrado__datos">
                    <span class="registrado__label">Teléfono</span>
                    <p class="registrado__dato"><?php echo $tercero->telefono?></p>
                </div>

            </div>
        </div>
    </section>


    <!-- UBICACION -->
    <section class="dashboard__contenedor">
        <div class="registrado">

            <h4 class="registrado__nombre">Ubicación</h4>

            <div class="registrado__contenedor">

                <div class="registrado__datos">
                    <span class="registrado__label">Dirección</span>
                    <p class="registrado__dato"><?php echo $tercero->direccion ?: '—'?></p>
                </div>

                <div class="registrado__datos">
                    <span class="registrado__label">Ciudad</span>
                    <p class="registrado__dato"><?php echo $tercero->ciudad ?: '—'?></p>
                </div>

                <div class="registrado__datos">
                    <span class="registrado__label">Correo</span>
                    <p class="registrado__dato"><?php echo $tercero->email ?: '—'?></p>
                </div>

            </div>
        </div>
    </section>

</div>
