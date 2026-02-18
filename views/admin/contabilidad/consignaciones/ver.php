<h3 class="dashboard__heading dashboard__heading--subscriptor">
    <span>Consignación: </span> <?php echo $titulo ?>
</h3>

<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton dashboard__boton--inline" href="/admin/contabilidad/consignaciones">
        <i class="fa-solid fa-arrow-left"></i>
        Volver
    </a>
</div>



<div class="flexbox">

    <!-- ============================= -->
    <!-- INFORMACIÓN CONSIGNACIÓN -->
    <!-- ============================= -->

    <section class="dashboard__contenedor">
        <div class="registrado">

            <h4 class="registrado__nombre">
                Información de la Consignación

                <?php if (!$consignacion->anulado): ?>
                    &nbsp;&nbsp;
                    <a class="registrado__accion" 
                       href="/admin/contabilidad/consignaciones/editar?id=<?php echo $consignacion->id ?>">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                <?php endif; ?>
            </h4>

            <div class="registrado__contenedor">

                <!-- ESTADO -->
                <div class="registrado__datos">
                    <span class="registrado__label">Estado</span>
                    <p class="registrado__dato">
                        <?php echo $consignacion->anulado ? 'ANULADO' : 'ACTIVO'; ?>
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
                        <?php echo $consignacion->fecha ?>
                    </p>
                </div>

                <!-- TIPO -->
                <div class="registrado__datos">
                    <span class="registrado__label">Tipo</span>
                    <p class="registrado__dato">
                        <?php echo ucfirst($consignacion->tipo); ?>
                    </p>
                </div>

                <!-- MONTO -->
                <div class="registrado__datos">
                    <span class="registrado__label">Monto</span>
                    <p class="registrado__dato">
                        $ <?php echo number_format($consignacion->monto, 2); ?>
                    </p>
                </div>

                <!-- DESCRIPCIÓN -->
                <div class="registrado__datos">
                    <span class="registrado__label">Descripción</span>
                    <p class="registrado__dato">
                        <?php echo $consignacion->descripcion ?: '-' ?>
                    </p>
                </div>

                <!-- COMPROBANTE -->
                <div class="registrado__datos">
                    <span class="registrado__label">Comprobante</span>
                    <p class="registrado__dato">
                        <?php if (!empty($consignacion->ruta_comprobante)): ?>
                            <a href="/storage/comprobantes/consignaciones/<?php echo $consignacion->ruta_comprobante; ?>" target="_blank">
                                Ver PDF
                            </a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </p>
                </div>

                <!-- RAZÓN DE ANULACIÓN (OPCIONAL SOLO SI ESTÁ ANULADA) -->
                <?php if ($consignacion->anulado && !empty($consignacion->razon_anulacion)): ?>
                    <div class="registrado__datos">
                        <span class="registrado__label">Razón de Anulación</span>
                        <p class="registrado__dato">
                            <?php echo $consignacion->razon_anulacion; ?>
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
