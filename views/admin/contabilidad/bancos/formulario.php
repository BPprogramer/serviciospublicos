<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información del Banco</legend>

    <div class="formulario__grid">

        <!-- NOMBRE -->
        <div class="formulario__campo">
            <label for="nombre" class="formulario__label">Nombre Banco:</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="Ej: Banco Agrario"
                id="nombre"
                name="nombre"
                value="<?php echo $banco->nombre ?? ''?>"
            >
        </div>

        <!-- CODIGO -->
        <div class="formulario__campo">
            <label for="codigo" class="formulario__label">Código:</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="Ej: 001"
                id="codigo"
                name="codigo"
                value="<?php echo $banco->codigo ?? ''?>"
            >
        </div>

    </div>

    <div class="formulario__grid">

        <!-- NIT -->
        <div class="formulario__campo">
            <label for="nit" class="formulario__label">NIT:</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="9 dígitos"
                id="nit"
                name="nit"
                value="<?php echo $banco->nit ?? ''?>"
            >
        </div>

        <!-- DV -->
        <div class="formulario__campo">
            <label for="dv" class="formulario__label">DV:</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="1 dígito"
                id="dv"
                name="dv"
                value="<?php echo $banco->dv ?? ''?>"
            >
        </div>

    </div>

</fieldset>
