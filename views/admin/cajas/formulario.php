
<fieldset class="formulario__fieldset"  id="caja">
    <legend class="formulario__legend" >Información de la caja</legend>

    <div class="formulario__campo">
        <label for="efectivo_inicial" class="formulario__label">Efectivo Inicial:</label>
        <input 
            type="text"
            class="formulario__input"
            placeholder="Ingrese el efectivo con el que va a iniciar la caja"
            id="efectivo_inicial"
            name="efectivo_inicial"
            value="<?php echo $caja->efectivo_inicial?>"
            >
    </div>

 
 
</fieldset>
