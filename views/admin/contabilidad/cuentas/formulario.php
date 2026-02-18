<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información de la Cuenta</legend>

    <div class="formulario__grid">

        <!-- CODIGO -->
        <div class="formulario__campo">
            <label for="codigo" class="formulario__label">Código:</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="Ej: 110505"
                id="codigo"
                name="codigo"
                value="<?php echo $cuenta->codigo ?? ''?>"
            >
        </div>

        <!-- NOMBRE -->
        <div class="formulario__campo">
            <label for="nombre" class="formulario__label">Nombre Cuenta:</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="Ej: Caja General"
                id="nombre"
                name="nombre"
                value="<?php echo $cuenta->nombre ?? ''?>"
            >
        </div>

    </div>

    <div class="formulario__grid">

        <!-- TIPO -->
        <div class="formulario__campo">
            <label for="tipo" class="formulario__label">Tipo de Cuenta:</label>
            <select class="formulario__select" name="tipo" id="tipo">
                <option value="">--Seleccione--</option>

                <option value="activo"  <?php echo ($cuenta->tipo ?? '')=='activo'?'selected':''?>>Activo</option>
                <option value="pasivo"  <?php echo ($cuenta->tipo ?? '')=='pasivo'?'selected':''?>>Pasivo</option>
                <option value="gasto"   <?php echo ($cuenta->tipo ?? '')=='gasto'?'selected':''?>>Gasto</option>
                <option value="ingreso" <?php echo ($cuenta->tipo ?? '')=='ingreso'?'selected':''?>>Ingreso</option>
            </select>
        </div>

    </div>

</fieldset>
