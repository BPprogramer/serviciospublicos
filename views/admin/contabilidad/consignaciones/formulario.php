<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información de la Consignación</legend>

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
                        <?php echo ($consignacion->cuenta_bancaria_id ?? '') == $cuenta->id ? 'selected' : ''; ?>
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
                value="<?php echo $consignacion->fecha ?? date('Y-m-d'); ?>"
            >
        </div>

    </div>

    <div class="formulario__grid">

        <!-- TIPO -->
        <div class="formulario__campo">
            <label for="tipo" class="formulario__label">Tipo:</label>
            <select 
                class="formulario__select"
                id="tipo"
                name="tipo"
            >
                <option value="">--Seleccione--</option>
                <option value="facturacion" <?php echo ($consignacion->tipo ?? '') === 'facturacion' ? 'selected' : ''; ?>>Facturación</option>
                <option value="subsidio" <?php echo ($consignacion->tipo ?? '') === 'subsidio' ? 'selected' : ''; ?>>Subsidio</option>
                <option value="otros" <?php echo ($consignacion->tipo ?? '') === 'otros' ? 'selected' : ''; ?>>Otros</option>
            </select>
        </div>

        <!-- MONTO -->
        <div class="formulario__campo">
            <label for="monto" class="formulario__label">Monto:</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="0.00"
                id="monto"
                name="monto"
                value="<?php echo $consignacion->monto ?? '' ?>"
            >
        </div>

    </div>

    <div class="formulario__grid">

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
            ><?php echo $consignacion->descripcion ?? '' ?></textarea>
        </div>

        <!-- COMPROBANTE (OPCIONAL) -->
        <div class="formulario__campo">
            <label for="ruta_comprobante" class="formulario__label">
                Comprobante (PDF opcional):
            </label>
            <input 
                type="file"
                class="formulario__input"
                id="ruta_comprobante"
                name="ruta_comprobante"
                accept="application/pdf"
            >

            <?php if (!empty($consignacion->ruta_comprobante)): ?>
                <p style="margin-top:8px;">
                    Archivo actual: 
                    <a href="/storage/comprobantes/consignaciones/<?php echo $consignacion->ruta_comprobante; ?>" target="_blank">
                        Ver comprobante
                    </a>
                </p>
            <?php endif; ?>
        </div>

    </div>

</fieldset>
