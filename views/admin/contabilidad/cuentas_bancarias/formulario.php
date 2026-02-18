<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información de la Cuenta Bancaria</legend>

    <div class="formulario__grid">

        <!-- BANCO -->
        <div class="formulario__campo">
            <label for="banco_id" class="formulario__label">Banco:</label>
            <select 
                class="formulario__select"
                id="banco_id"
                name="banco_id"
            >
                <option value="">--Seleccione--</option>

                <?php foreach($bancos as $banco): ?>
                    <option 
                        value="<?php echo $banco->id; ?>"
                        <?php echo ($cuenta->banco_id ?? '') == $banco->id ? 'selected' : ''; ?>
                    >
                        <?php echo $banco->nombre; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- NUMERO CUENTA -->
        <div class="formulario__campo">
            <label for="numero_cuenta" class="formulario__label">Número de Cuenta:</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="Ej: 123456789"
                id="numero_cuenta"
                name="numero_cuenta"
                value="<?php echo $cuenta->numero_cuenta ?? ''?>"
            >
        </div>

    </div>

    <div class="formulario__grid">

        <!-- NOMBRE CUENTA -->
        <div class="formulario__campo">
            <label for="nombre" class="formulario__label">Nombre de la Cuenta:</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="Ej: Cuenta Principal"
                id="nombre"
                name="nombre"
                value="<?php echo $cuenta->nombre ?? ''?>"
            >
        </div>

        <!-- SALDO INICIAL -->
        <div class="formulario__campo">
            <label for="saldo_inicial" class="formulario__label">Saldo Inicial:</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="0.00"
                id="saldo_inicial"
                name="saldo_inicial"
                value="<?php echo $cuenta->saldo_inicial ?? '0.00'?>"
            >
        </div>

    </div>

</fieldset>
