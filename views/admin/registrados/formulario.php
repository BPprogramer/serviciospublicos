<fieldset class="formulario__fieldset">
    <legend class="formulario__legend" >Información Personal</legend>

    <div class="formulario__grid">
        <div class="formulario__campo">
            <label for="nombre" class="formulario__label">Nombre:</label>
            <input 
                type="nombre"
                class="formulario__input"
                placeholder="Nombre"
                id="nombre"
                name="nombre"
                value="<?php echo $registrado->nombre?>"
                >
        </div>
        <div class="formulario__campo">
            <label for="apellido" class="formulario__label">Apellido:</label>
            <input 
                type="apellido"
                class="formulario__input"
                placeholder="Apellido"
                id="apellido"
                name="apellido"
                value="<?php echo $registrado->apellido?>"
                >
        </div>
    </div>


   <div class="formulario__grid">
        <div class="formulario__campo">
            <label for="cedula_nit" class="formulario__label">Cédula o Nit:</label>
            <input 
                type="cedula_nit"
                class="formulario__input"
                placeholder="cedula o Nit de su Empresa"
                id="cedula_nit"
                name="cedula_nit"
                value="<?php echo $registrado->cedula_nit?>"
                >
        </div>
        <div class="formulario__campo">
            <label for="celular" class="formulario__label">Celular:</label>
            <input 
                type="number"
                class="formulario__input"
                placeholder="Numero de Celular"
                id="celular"
                name="celular"
                value="<?php echo $registrado->celular?>"
                >
        </div>
   </div>

   <div class="formulario__grid">
        <div class="formulario__campo">
            <label for="barrio" class="formulario__label">Barrio:</label>
            <input 
                type="barrio"
                class="formulario__input"
                placeholder="Barrio"
                id="barrio"
                name="barrio"
                value="<?php echo $registrado->barrio?>"
                >
        </div>
    
        <div class="formulario__campo">
            <label for="direccion" class="formulario__label">direccion:</label>
            <input 
                type="direccion"
                class="formulario__input"
                placeholder="Dirección"
                id="direccion"
                name="direccion"
                value="<?php echo $registrado->direccion?>"
                >
        </div>
   </div>
   
   <div class="formulario__grid">
        <div class="formulario__campo">
                <label for="codigo_ubicacion" class="formulario__label">Código Ubicación:</label>
                <input 
                    type="codigo_ubicacion"
                    class="formulario__input"
                    placeholder="Código de Ubicación"
                    id="codigo_ubicacion"
                    name="codigo_ubicacion"
                    value="<?php echo $registrado->codigo_ubicacion?>"
                    >
            </div>
        <div class="formulario__campo">
            <label for="descripcion" class="formulario__label">Estrato</label>
            <select 
                class="formulario__select" 
                name="estrato_id" 
                id="admin"
            >
            
            <option disabled selected>--Seleccione--</option>

            <?php foreach($estratos as $estrato){?>
                
                <option value="<?php echo $estrato->id?>" <?php echo $registrado->estrato_id == $estrato->id?'selected':''?>><?php echo $estrato->estrato?></option>
            <?php }?>

                   
                    
        
            </select>
        </div>
   </div>
   
    
   
   

  
  


</fieldset>