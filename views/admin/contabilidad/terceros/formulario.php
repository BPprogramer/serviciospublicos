<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información del Tercero</legend>

    <div class="formulario__grid">

        <div class="formulario__campo">
            <label for="tipo_persona" class="formulario__label">Tipo Persona:</label>
            <select class="formulario__select" name="tipo_persona" id="tipo_persona">
                <option value="">--Seleccione--</option>
                <option value="natural" <?php echo $tercero->tipo_persona=='natural'?'selected':''?>>Natural</option>
                <option value="juridica" <?php echo $tercero->tipo_persona=='juridica'?'selected':''?>>Jurídica</option>
            </select>
        </div>

        <div class="formulario__campo">
            <label for="nombre" class="formulario__label">Nombre / Razón Social:</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="Nombre o empresa"
                id="nombre"
                name="nombre"
                value="<?php echo $tercero->nombre?>"
            >
        </div>

    </div>

    <div class="formulario__grid">

        <div class="formulario__campo">
            <label for="documento" class="formulario__label">Documento / NIT:</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="Número de documento"
                id="documento"
                name="documento"
                value="<?php echo $tercero->documento?>"
            >
        </div>

        <div class="formulario__campo">
            <label for="dv" class="formulario__label">DV:</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="Dígito verificación"
                id="dv"
                name="dv"
                value="<?php echo $tercero->dv?>"
            >
        </div>

    </div>

    <div class="formulario__grid">

        <div class="formulario__campo">
            <label for="telefono" class="formulario__label">Teléfono:</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="Número de contacto"
                id="telefono"
                name="telefono"
                value="<?php echo $tercero->telefono?>"
            >
        </div>

        <div class="formulario__campo">
            <label for="email" class="formulario__label">Email:</label>
            <input 
                type="email"
                class="formulario__input"
                placeholder="Correo electrónico"
                id="email"
                name="email"
                value="<?php echo $tercero->email?>"
            >
        </div>

    </div>

    <div class="formulario__grid">

        <div class="formulario__campo">
            <label for="direccion" class="formulario__label">Dirección:</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="Dirección"
                id="direccion"
                name="direccion"
                value="<?php echo $tercero->direccion?>"
            >
        </div>

        <div class="formulario__campo">
            <label for="ciudad" class="formulario__label">Ciudad:</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="Ciudad"
                id="ciudad"
                name="ciudad"
                value="<?php echo $tercero->ciudad?>"
            >
        </div>

    </div>

</fieldset>
