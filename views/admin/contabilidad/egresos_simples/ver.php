<h3 class="dashboard__heading dashboard__heading--subscriptor">
    <span>Egreso Simple: </span> <?php echo $titulo ?>
</h3>

<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton dashboard__boton--inline" href="/admin/contabilidad/egresos-simples">
        <i class="fa-solid fa-arrow-left"></i>
        Volver
    </a>
</div>

<div class="flexbox">

    <!-- ============================= -->
    <!-- INFORMACIÓN EGRESO SIMPLE -->
    <!-- ============================= -->

    <section class="dashboard__contenedor">
        <div class="registrado">

            <h4 class="registrado__nombre">
                Información del Egreso

                <?php if (!$egreso->anulado): ?>
                    &nbsp;&nbsp;
                    <a class="registrado__accion" 
                       href="/admin/contabilidad/egresos-simples/editar?id=<?php echo $egreso->id ?>">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                <?php endif; ?>
            </h4>

            <div class="registrado__contenedor">

                <!-- ESTADO -->
                <div class="registrado__datos">
                    <span class="registrado__label">Estado</span>
                    <p class="registrado__dato">
                        <?php echo $egreso->anulado ? 'ANULADO' : 'ACTIVO'; ?>
                    </p>
                </div>

                <!-- CUENTA -->
                <div class="registrado__datos">
                    <span class="registrado__label">Cuenta Bancaria</span>
                    <p class="registrado__dato">
                        <?php echo $cuentaBancaria->numero_cuenta ?? '-' ?>
                    </p>
                </div>

                <!-- FECHA -->
                <div class="registrado__datos">
                    <span class="registrado__label">Fecha</span>
                    <p class="registrado__dato">
                        <?php echo $egreso->fecha ?>
                    </p>
                </div>

                <!-- MONTO -->
                <div class="registrado__datos">
                    <span class="registrado__label">Monto</span>
                    <p class="registrado__dato">
                        $ <?php echo number_format($egreso->monto, 2); ?>
                    </p>
                </div>

                <!-- DESCRIPCIÓN -->
                <div class="registrado__datos">
                    <span class="registrado__label">Descripción</span>
                    <p class="registrado__dato">
                        <?php echo $egreso->descripcion ?: '-' ?>
                    </p>
                </div>

                <!-- 🔥 RAZÓN DE ANULACIÓN -->
                <?php if ($egreso->anulado): ?>
                    <div class="registrado__datos">
                        <span class="registrado__label">Razón de Anulación</span>
                        <p class="registrado__dato">
                            <?php echo $egreso->razon_anulacion ?: '-'; ?>
                        </p>
                    </div>
                <?php endif; ?>

            </div>

        </div>
    </section>


    <!-- ============================= -->
    <!-- RESPONSABLE -->
    <!-- ============================= -->

    <section class="dashboard__contenedor">
        <div class="registrado">

            <h4 class="registrado__nombre">
                Responsable
            </h4>

            <div class="registrado__contenedor">

                <div class="registrado__datos">
                    <span class="registrado__label">Nombre</span>
                    <p class="registrado__dato">
                        <?php
                        echo $responsable
                            ? $responsable->nombre . ' ' . $responsable->apellido
                            : '-';
                        ?>
                    </p>
                </div>

            </div>

        </div>
    </section>

</div>
