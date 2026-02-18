<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información del Egreso</legend>

    <div class="formulario__grid">

        <!-- FECHA -->
        <div class="formulario__campo">
            <label class="formulario__label">Fecha:</label>
            <input
                type="date"
                class="formulario__input"
                name="fecha"
                value="<?php echo $egreso->fecha ?? date('Y-m-d'); ?>">
        </div>

        <!-- TERCERO -->
        <div class="formulario__campo">
            <label class="formulario__label">Tercero:</label>
            <select name="tercero_id" class="formulario__select">
                <option value="">--Seleccione--</option>
                <?php foreach ($terceros as $tercero): ?>
                    <option
                        value="<?php echo $tercero->id; ?>"
                        <?php echo ($egreso->tercero_id ?? '') == $tercero->id ? 'selected' : ''; ?>>
                        <?php echo $tercero->nombre; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

    </div>

    <div class="formulario__grid">

        <!-- CUENTA BANCARIA -->
        <div class="formulario__campo">
            <label class="formulario__label">Cuenta Bancaria:</label>
            <select name="cuenta_bancaria_id" class="formulario__select">
                <option value="">--Seleccione--</option>
                <?php foreach ($cuentasBancarias as $cb): ?>
                    <option
                        value="<?php echo $cb->id; ?>"
                        <?php echo ($egreso->cuenta_bancaria_id ?? '') == $cb->id ? 'selected' : ''; ?>>
                        <?php echo $cb->numero_cuenta; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- DETALLE -->
        <div class="formulario__campo">
            <label class="formulario__label">Detalle:</label>
            <input
                type="text"
                class="formulario__input"
                name="detalle"
                value="<?php echo $egreso->detalle ?? ''; ?>">
        </div>

    </div>
</fieldset>


<!-- ========================= -->
<!-- LINEAS CONTABLES -->
<!-- ========================= -->

<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Cuentas Contables</legend>

    <table class="tabla-lineas">
        <thead>
            <tr>
                <th>Cuenta</th>
                <th>Débito</th>
                <th>Crédito</th>
                <th></th>
            </tr>
        </thead>

        <tbody id="js-lineas-egreso">

            <?php
            $lineasForm = [];

            // 1️⃣ Si vienen líneas desde POST (crear o editar con error)
            if (!empty($_POST['cuentas'])) {
                $lineasForm = $_POST['cuentas'];
            }
            // 2️⃣ Si es edición y existen líneas desde BD
            elseif (!empty($lineas)) {
                foreach ($lineas as $l) {
                    $lineasForm[] = [
                        'cuenta_id' => $l->cuenta_id,
                        'debito' => $l->debito,
                        'credito' => $l->credito
                    ];
                }
            }
            // 3️⃣ Si no hay nada → una línea vacía
            else {
                $lineasForm[] = [
                    'cuenta_id' => '',
                    'debito' => '',
                    'credito' => ''
                ];
            }
            ?>

            <?php foreach ($lineasForm as $index => $linea): ?>
                <tr>
                    <td>
                        <select name="cuentas[<?php echo $index; ?>][cuenta_id]"
                            class="formulario__select js-cuenta-select">

                            <option value="">--Seleccione--</option>

                            <?php foreach ($cuentas as $cuenta): ?>
                                <option value="<?php echo $cuenta->id; ?>"
                                    <?php echo ($linea['cuenta_id'] ?? '') == $cuenta->id ? 'selected' : ''; ?>>
                                    <?php echo $cuenta->codigo . ' - ' . $cuenta->nombre; ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </td>

                    <td>
                        <input type="text"
                            name="cuentas[<?php echo $index; ?>][debito]"
                            class="formulario__input js-debito"
                            value="<?php echo $linea['debito'] ?? ''; ?>">
                    </td>

                    <td>
                        <input type="text"
                            name="cuentas[<?php echo $index; ?>][credito]"
                            class="formulario__input js-credito"
                            value="<?php echo $linea['credito'] ?? ''; ?>">
                    </td>

                    <td>
                        <button type="button" class="btnEliminarLinea">X</button>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>


    </table>

    <div style="margin-top:15px;">
        <button type="button" class="dashboard__boton js-agregar-linea">
            + Agregar Línea
        </button>
    </div>

    <div style="margin-top:20px;">
        <strong>Total Débito:</strong>
        <span id="js-total-debito">0.00</span>
        &nbsp;&nbsp;
        <strong>Total Crédito:</strong>
        <span id="js-total-credito">0.00</span>
    </div>

</fieldset>