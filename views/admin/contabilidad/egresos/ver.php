<h3 class="dashboard__heading dashboard__heading--subscriptor">
    <span>Egreso: </span> <?php echo $titulo ?>
</h3>

<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton dashboard__boton--inline" href="/admin/contabilidad/egresos">
        <i class="fa-solid fa-arrow-left"></i>
        Volver
    </a>
</div>

<div class="flexbox">

    <!-- ===================================== -->
    <!-- INFORMACIÓN DEL EGRESO -->
    <!-- ===================================== -->

    <section class="dashboard__contenedor">
        <div class="registrado">

            <h4 class="registrado__nombre">
                Información del Egreso
                &nbsp;&nbsp;
                <a class="registrado__accion" href="/admin/contabilidad/egresos/editar?id=<?php echo $egreso->id ?>">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
            </h4>

            <div class="registrado__contenedor">

                <div class="registrado__datos">
                    <span class="registrado__label">Consecutivo</span>
                    <p class="registrado__dato"><?php echo $egreso->consecutivo; ?></p>
                </div>

                <div class="registrado__datos">
                    <span class="registrado__label">Fecha</span>
                    <p class="registrado__dato"><?php echo $egreso->fecha; ?></p>
                </div>

                <div class="registrado__datos">
                    <span class="registrado__label">Detalle</span>
                    <p class="registrado__dato"><?php echo $egreso->detalle ?: '-'; ?></p>
                </div>

                <div class="registrado__datos">
                    <span class="registrado__label">Estado</span>
                    <p class="registrado__dato">
                        <?php echo $egreso->anulado ? 'ANULADO' : 'ACTIVO'; ?>
                    </p>
                </div>

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


    <!-- ===================================== -->
    <!-- INFORMACIÓN DEL TERCERO -->
    <!-- ===================================== -->

    <section class="dashboard__contenedor">
        <div class="registrado">

            <h4 class="registrado__nombre">
                Información del Tercero
            </h4>

            <div class="registrado__contenedor">

                <div class="registrado__datos">
                    <span class="registrado__label">Nombre</span>
                    <p class="registrado__dato"><?php echo $tercero->nombre; ?></p>
                </div>

                <div class="registrado__datos">
                    <span class="registrado__label">Documento</span>
                    <p class="registrado__dato"><?php echo $tercero->documento; ?></p>
                </div>

            </div>

        </div>
    </section>


    <!-- ===================================== -->
    <!-- INFORMACIÓN CUENTA BANCARIA -->
    <!-- ===================================== -->

    <section class="dashboard__contenedor">
        <div class="registrado">

            <h4 class="registrado__nombre">
                Cuenta Bancaria
            </h4>

            <div class="registrado__contenedor">

                <div class="registrado__datos">
                    <span class="registrado__label">Número de Cuenta</span>
                    <p class="registrado__dato"><?php echo $cuentaBancaria->numero_cuenta; ?></p>
                </div>

                <div class="registrado__datos">
                    <span class="registrado__label">Nombre</span>
                    <p class="registrado__dato"><?php echo $cuentaBancaria->nombre ?: '-'; ?></p>
                </div>

            </div>

        </div>
    </section>

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


<!-- ===================================== -->
<!-- DETALLE CONTABLE -->
<!-- ===================================== -->

<section class="dashboard__contenedor">
    <div class="registrado">

        <h4 class="registrado__nombre">
            Detalle Contable
        </h4>

        <div class="registrado__contenedor">

            <table class="table">
                <thead>
                    <tr>
                        <th>Cuenta</th>
                        <th>Débito</th>
                        <th>Crédito</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    $totalDebito = 0;
                    $totalCredito = 0;
                    ?>

                    <?php foreach ($lineas as $linea): ?>

                        <?php
                        $totalDebito += $linea->debito;
                        $totalCredito += $linea->credito;
                        ?>

                        <tr>
                            <td>
                                <?php echo $linea->cuenta_id; ?>
                            </td>
                            <td>
                                <?php echo number_format($linea->debito, 2); ?>
                            </td>
                            <td>
                                <?php echo number_format($linea->credito, 2); ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>

                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th><?php echo number_format($totalDebito, 2); ?></th>
                        <th><?php echo number_format($totalCredito, 2); ?></th>
                    </tr>
                </tfoot>

            </table>

        </div>

    </div>
</section>