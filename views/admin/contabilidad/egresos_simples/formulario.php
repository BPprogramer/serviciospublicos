<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información del Egreso Simple</legend>

    <div class="formulario__grid">

        <!-- CUENTA BANCARIA -->
        <div class="formulario__campo">
            <label for="cuenta_bancaria_id" class="formulario__label">
                Cuenta Bancaria:
            </label>

            <select 
                class="formulario__select"
                id="cuenta_bancaria_id"
                name="cuenta_bancaria_id"
            >
                <option value="">--Seleccione--</option>

                <?php foreach($cuentasBancarias as $cuenta): ?>
                    <option 
                        value="<?php echo $cuenta->id; ?>"
                        <?php echo ($egreso->cuenta_bancaria_id ?? '') == $cuenta->id ? 'selected' : ''; ?>
                    >
                        <?php echo $cuenta->numero_cuenta . ' - ' . $cuenta->nombre; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- FECHA -->
        <div class="formulario__campo">
            <label for="fecha" class="formulario__label">Fecha:</label>
            <input 
                type="date"
                class="formulario__input"
                id="fecha"
                name="fecha"
                value="<?php echo $egreso->fecha ?? date('Y-m-d'); ?>"
            >
        </div>

    </div>

    <div class="formulario__grid">

        <!-- MONTO -->
        <div class="formulario__campo">
            <label for="monto" class="formulario__label">Monto:</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="0.00"
                id="monto"
                name="monto"
                value="<?php echo $egreso->monto ?? '' ?>"
            >
        </div>

        <!-- DESCRIPCIÓN -->
        <div class="formulario__campo">
            <label for="descripcion" class="formulario__label">
                Descripción:
            </label>
            <textarea 
                class="formulario__input"
                id="descripcion"
                name="descripcion"
                rows="4"
            ><?php echo $egreso->descripcion ?? '' ?></textarea>
        </div>

    </div>

</fieldset>
