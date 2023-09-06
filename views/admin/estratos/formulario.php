
<fieldset class="formulario__fieldset"  id="estrato">
    <legend class="formulario__legend" >Información del Estrato</legend>

    <div class="formulario__campo">
        <label for="estrato" class="formulario__label">Estrato:</label>
        <input 
            type="estrato"
            class="formulario__input"
            placeholder="Nombre del Estrato"
            id="estrato"
            name="estrato"
            value="<?php echo $estratos->estrato?>"
            >
    </div>
    <div class="formulario__grid">
        <div class="formulario__campo">
            <label for="year" class="formulario__label">Año Vigente:</label>
            <select name="year" class="formulario__select" id="year">
                <option value="">--Seleccionar año--</option>
                <?php for($i = 0; $i<=10; $i++){ 
                    $year = $year+1;
                ?>

                    <option class="formulario__option" value="<?php echo $year?>" <?php echo $estratos->year == $year ? 'selected':''?>><?php echo $year?></option>
                <?php }?>
            </select>
        </div>
        <div class="formulario__campo">
            <label for="facturas_vencidas" class="formulario__label">Facturas Vencidas :</label>
            <input 
                type="number"
                class="formulario__input"
                placeholder="Nombre del facturas_vencidas"
                id="facturas_vencidas"
                name="facturas_vencidas"
                value="<?php echo $estratos->facturas_vencidas?>"
                >
        </div>
    </div>
    
    <div class="formulario__grid">
        <div class="formulario__campo">
            <label for="tarifa_plena" class="formulario__label">Tarifa Plena:</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="Ej 10,000"
                id="tarifa_plena"
                name="tarifa_plena"
                value="<?php echo $estratos->tarifa_plena?>"
                >
        </div>
    
        <div class="formulario__campo">
            <label for="porcentaje_subsidio" class="formulario__label">Porcentaje Subsidio:</label>
            <input 
                type="number"
                class="formulario__input"
                placeholder="Ej (50%)"
                id="porcentaje_subsidio"
                name="porcentaje_subsidio"
                min="0"
                max="100"
                value="<?php echo $estratos->porcentaje_subsidio?>"
                >
        </div>
     
    </div>
    <div class="formulario__grid">
        <div class="formulario__campo">
            <label for="subsidio" class="formulario__label">Subsidio Total:</label>
            <input 
                type="text"
                class="formulario__input"
       
                id="subsidio"
                name="subsidio"
                value="<?php echo $estratos->subsidio?>"
                readonly
                >
        </div>
    
        <div class="formulario__campo">
            <label for="copago" class="formulario__label">Copago:</label>
            <input 
                type="copago"
                class="formulario__input"
                id="copago"
                name="copago"
                value="<?php echo $estratos->copago?>"
                readonly
                >
        </div>
     
    </div>
    
 
 
</fieldset>

<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Acueducto</legend>
    <div class="formulario__grid">
        <div class="formulario__campo">
            <label for="porcentaje_acu" class="formulario__label">Porcentaje Acueducto</label>
            <input 
                type="number"
                class="formulario__input"
                placeholder="Ejemplo 50%"
                id="porcentaje_acu"
                name="porcentaje_acu"
                min="0"
                max="100"
                value="<?php echo $estratos->porcentaje_acu?>"
       
                >
        </div>

        <div class="formulario__campo">
            <label for="tarifa_plena_acu" class="formulario__label">Total Acueducto:</label>
            <input 
                type="text"
                class="formulario__input"
       
                id="tarifa_plena_acu"
                name="tarifa_plena_acu"
                value="<?php echo $estratos->tarifa_plena_acu?>"
                readonly
                >
        </div>
    
        <div class="formulario__campo">
            <label for="subsidio_acu" class="formulario__label">Subsidio Acueducto:</label>
            <input 
                type="subsidio_acu"
                class="formulario__input"
                id="subsidio_acu"
                name="subsidio_acu"
                value="<?php echo $estratos->subsidio_acu?>"
                readonly
                >
        </div>
       
    
        <div class="formulario__campo">
            <label for="copago_acu" class="formulario__label">Copago Acueducto:</label>
            <input 
                type="copago_acu"
                class="formulario__input"
                id="copago_acu"
                name="copago_acu"
                value="<?php echo $estratos->copago_acu?>"
                readonly
                >
        </div>
     
    </div>
    
</fieldset>
<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Alcantarillado</legend>
    <div class="formulario__grid">
        <div class="formulario__campo">
            <label for="porcentaje_alc" class="formulario__label">Porcentaje Alcantarillado</label>
            <input 
                type="number"
                class="formulario__input"
                placeholder="Ejemplo 50%"
                id="porcentaje_alc"
                name="porcentaje_alc"
                min="0"
                max="100"
                value="<?php echo $estratos->porcentaje_alc?>"
       
                >
        </div>

        <div class="formulario__campo">
            <label for="tarifa_plena_alc" class="formulario__label">Total Alcantarillado:</label>
            <input 
                type="text"
                class="formulario__input"
       
                id="tarifa_plena_alc"
                name="tarifa_plena_alc"
                value="<?php echo $estratos->tarifa_plena_alc?>"
                readonly
                >
        </div>
    
        <div class="formulario__campo">
            <label for="subsidio_alc" class="formulario__label">Subsidio Alcantarillado:</label>
            <input 
                type="subsidio_alc"
                class="formulario__input"
                id="subsidio_alc"
                name="subsidio_alc"
                value="<?php echo $estratos->subsidio_alc?>"
                readonly
                >
        </div>
       
    
        <div class="formulario__campo">
            <label for="copago_alc" class="formulario__label">Copago Alcantarillado:</label>
            <input 
                type="copago_alc"
                class="formulario__input"
                id="copago_alc"
                name="copago_alc"
                value="<?php echo $estratos->copago_alc?>"
                readonly
                >
        </div>
     
    </div>
    
</fieldset>
<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Aseo</legend>
    <div class="formulario__grid">
        <div class="formulario__campo">
            <label for="porcentaje_aseo" class="formulario__label">Porcentaje Aseo</label>
            <input 
                type="number"
                class="formulario__input"
                placeholder="Ejemplo 50%"
                id="porcentaje_aseo"
                name="porcentaje_aseo"
                min="0"
                max="100"
                value="<?php echo $estratos->porcentaje_aseo?>"
       
                >
        </div>

        <div class="formulario__campo">
            <label for="tarifa_plena_aseo" class="formulario__label">Total Aseo:</label>
            <input 
                type="text"
                class="formulario__input"
       
                id="tarifa_plena_aseo"
                name="tarifa_plena_aseo"
                value="<?php echo $estratos->tarifa_plena_aseo?>"
                readonly
                >
        </div>
    
        <div class="formulario__campo">
            <label for="subsidio_aseo" class="formulario__label">Subsidio Aseo:</label>
            <input 
                type="subsidio_aseo"
                class="formulario__input"
                id="subsidio_aseo"
                name="subsidio_aseo"
                value="<?php echo $estratos->subsidio_aseo?>"
                readonly
                >
        </div>
       
    
        <div class="formulario__campo">
            <label for="copago_aseo" class="formulario__label">Copago Aseo:</label>
            <input 
                type="copago_aseo"
                class="formulario__input"
                id="copago_aseo"
                name="copago_aseo"
                value="<?php echo $estratos->copago_aseo?>"
                readonly    
            >
        </div>
     
    </div>
    
</fieldset>
<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Ajuste</legend>
    <div class="formulario__grid">

         <div class="formulario__campo">
            <label for="ajuste" class="formulario__label">Valor Ajuste:</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="Ejemplo 10,000"
                id="ajuste"
                name="ajuste"
                value="<?php echo $estratos->ajuste?>"
  
            >
        </div>
        <div class="formulario__campo">
            <label for="porcentaje_ajuste" class="formulario__label">Porcentaje Ajuste</label>
            <input 
                type="number"
                class="formulario__input"
                placeholder="Ejemplo 50%"
                id="porcentaje_ajuste"
                name="porcentaje_ajuste"
                min="0"
                max="100"
                value="<?php echo $estratos->porcentaje_ajuste?>"
                >
        </div>
     
    </div>
    
</fieldset>