<fieldset class="formulario__fieldset">
    <legend class="formulario__legend" >Información Personal</legend>

    <div class="formulario__campo">
        <label for="nombre" class="formulario__label">Nombre:</label>
        <input 
            type="nombre"
            class="formulario__input"
            placeholder="Tu Nombre"
            id="nombre"
            name="nombre"
            value="<?php echo $usuario->nombre?>"
            >
    </div>
    <div class="formulario__campo">
        <label for="apellido" class="formulario__label">Apellido:</label>
        <input 
            type="apellido"
            class="formulario__input"
            placeholder="Tu Apellido"
            id="apellido"
            name="apellido"
            value="<?php echo $usuario->apellido?>"
            >
    </div>
    <div class="formulario__campo">
        <label for="email" class="formulario__label">Email:</label>
        <input 
            type="email"
            class="formulario__input"
            placeholder="Tu Email"
            id="email"
            name="email"
            value="<?php echo $usuario->email?>"
            >
    </div>
    <div class="formulario__campo">
        <label for="descripcion" class="formulario__label">tipo de usuario</label>
        <select 
            class="formulario__select" 
            name="admin" 
            id="admin"
        >

                <option <?php echo $usuario->confirmado == 0? 'selected': ''?> value="0">Facturador</option>
                <option <?php echo $usuario->confirmado == 1? 'selected': ''?> value="1">Administrador</option>
     
        </select>
    </div>
    <div class="formulario__campo">
        <label for="descripcion" class="formulario__label">Estado</label>
        <select 
            class="formulario__select" 
            name="confirmado" 
            id="confirmado"
        >

                <option <?php echo $usuario->confirmado == 0? 'selected': ''?> value="0">Inactivo</option>
                <option <?php echo $usuario->confirmado == 1? 'selected': ''?> value="1">Activo</option>
     
        </select>
    </div>
   

    <div class="formulario__campo">
        <label for="password" class="formulario__label">Password:</label>
        <input 
            type="password"
            class="formulario__input"
            placeholder="Tu Password"
            id="password"
            name="password"
            >
    </div>
    <div class="formulario__campo">
        <label for="password2" class="formulario__label">Repetir Password:</label>
        <input 
            type="password"
            class="formulario__input"
            placeholder="repetir Password"
            id="password2"
            name="password2"
            >
    </div>
  
  


</fieldset>