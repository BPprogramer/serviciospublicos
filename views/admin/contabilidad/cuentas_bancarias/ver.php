<h3 class="dashboard__heading dashboard__heading--subscriptor">
    <span>Cuenta Bancaria: </span> <?php echo $titulo?>
</h3>

<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton dashboard__boton--inline" href="/admin/contabilidad/cuentas-bancarias">
        <i class="fa-solid fa-arrow-left"></i>
        Volver
    </a>
</div>




<div class="flexbox">

    <!-- ============================= -->
    <!-- INFORMACIÓN CUENTA BANCARIA -->
    <!-- ============================= -->

    <section class="dashboard__contenedor">
        <div class="registrado">

            <h4 class="registrado__nombre">
                Información de la Cuenta Bancaria
                &nbsp;&nbsp;
                <a class="registrado__accion" href="/admin/contabilidad/cuentas-bancarias/editar?id=<?php echo $cuenta->id?>">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
            </h4>

            <div class="registrado__contenedor">

                <!-- BANCO ASOCIADO -->
                <div class="registrado__datos">
                    <span class="registrado__label">Banco</span>
                    <p class="registrado__dato"><?php echo $banco->nombre; ?></p>
                </div>

                <div class="registrado__datos">
                    <span class="registrado__label">Número de Cuenta</span>
                    <p class="registrado__dato"><?php echo $cuenta->numero_cuenta?></p>
                </div>

                <div class="registrado__datos">
                    <span class="registrado__label">Nombre</span>
                    <p class="registrado__dato"><?php echo $cuenta->nombre ?: '-'?></p>
                </div>

                <div class="registrado__datos">
                    <span class="registrado__label">Saldo Inicial</span>
                    <p class="registrado__dato">
                        <?php echo number_format($cuenta->saldo_inicial, 2); ?>
                    </p>
                </div>

            </div>

        </div>
    </section>


    <!-- ============================= -->
    <!-- INFORMACIÓN BANCO (DETALLE) -->
    <!-- ============================= -->

    <section class="dashboard__contenedor">
        <div class="registrado">

            <h4 class="registrado__nombre">
                Detalle del Banco
                &nbsp;&nbsp;
                <a class="registrado__accion" href="/admin/contabilidad/bancos/ver?id=<?php echo $banco->id?>">
                    <i class="fa-solid fa-search"></i>
                </a>
            </h4>

            <div class="registrado__contenedor">

                <div class="registrado__datos">
                    <span class="registrado__label">Nombre</span>
                    <p class="registrado__dato"><?php echo $banco->nombre?></p>
                </div>

                <div class="registrado__datos">
                    <span class="registrado__label">Código</span>
                    <p class="registrado__dato"><?php echo $banco->codigo ?: '-'?></p>
                </div>

                <div class="registrado__datos">
                    <span class="registrado__label">NIT</span>
                    <p class="registrado__dato"><?php echo $banco->nit ?: '-'?></p>
                </div>

                <div class="registrado__datos">
                    <span class="registrado__label">DV</span>
                    <p class="registrado__dato"><?php echo $banco->dv ?: '-'?></p>
                </div>

            </div>

        </div>
    </section>

</div>

<div style="margin-bottom:30px;padding:25px;background:#f8f9fa;border-radius:12px;">

    <h2 style="text-align:center;margin-bottom:20px;">
        Resumen Financiero
    </h2>

    <div style="max-width:600px;margin:0 auto;font-size:16px;">

        <!-- SALDO INICIAL -->
        <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
            <span>Saldo Inicial</span>
            <strong>$ <?php echo number_format($cuenta->saldo_inicial, 2); ?></strong>
        </div>

        <!-- + INGRESOS -->
        <div style="display:flex;justify-content:space-between;margin-bottom:8px;color:#28a745;">
            <span>+ Consignaciones</span>
            <strong>+ $ <?php echo number_format($totalConsignaciones, 2); ?></strong>
        </div>

        <!-- - EGRESOS SIMPLES -->
        <div style="display:flex;justify-content:space-between;margin-bottom:8px;color:#dc3545;">
            <span>- Egresos Simples</span>
            <strong>- $ <?php echo number_format($totalEgresosSimples, 2); ?></strong>
        </div>

        <!-- - EGRESOS CONTABLES -->
        <div style="display:flex;justify-content:space-between;margin-bottom:12px;color:#dc3545;">
            <span>- Egresos</span>
            <strong>- $ <?php echo number_format($totalEgresos, 2); ?></strong>
        </div>

        <hr>

        <!-- SALDO ACTUAL -->
        <div style="display:flex;justify-content:space-between;font-size:20px;font-weight:bold;
            color:<?php echo $saldoActual >= 0 ? '#28a745' : '#dc3545'; ?>;">
            <span>= Saldo Actual</span>
            <span>$ <?php echo number_format($saldoActual, 2); ?></span>
        </div>

    </div>

</div>


   <?php include_once __DIR__.'/movimientos.php'?>