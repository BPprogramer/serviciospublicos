<h3 class="dashboard__heading dashboard__heading--subscriptor"><span>Subscriptor: </span> <?php echo $titulo?></h3>
<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton" href="/admin/registrados">
        <i class="fa-solid fa-circle-plus"></i>
        Volver
    </a>
</div>

<div class="flexbox">
    <section class="dashboard__contenedor" id="contenedorRegistrados">
        <div class="registrado">
            <div class="registrado_titulo">
                
            </div>
            <h4 class="registrado__nombre">Información Subscriptor  &nbsp; &nbsp;<a class='registrado__accion' href='/admin/registrados/editar?id=<?php echo $registrado->id?>'><i class="fa-solid fa-pen-to-square"></i></a></h4>
            <div class="registrado__contenedor">
                <div class="registrado__datos">
                    <span class="registrado__label">Nombre </span>
                  
                    <p class="registrado__dato" id="cliente"><?php echo explode(' ',$registrado->nombre)[0]. ' '.$registrado->apellido?></p>
                </div>
                <div class="registrado__datos">
                    <span class="registrado__label">Cedula / nit</span>
                    <p class="registrado__dato"><?php echo $registrado->cedula_nit?></p>
                </div>
            
                <div class="registrado__datos">
                    <span class="registrado__label">Direccion</span>
                    <p class="registrado__dato"><?php echo $registrado->direccion?></p>
                </div>
                <div class="registrado__datos">
                    <span class="registrado__label">Barrio</span>
                    <p class="registrado__dato"><?php echo $registrado->barrio?></p>
                </div>
                <div class="registrado__datos">
                    <span class="registrado__label">Código </span>
                    <p class="registrado__dato"><?php echo $registrado->codigo_ubicacion?></p>
                </div>
                <div class="registrado__datos">
                    <span class="registrado__label">Celular</span>
                    <p class="registrado__dato"><?php echo $registrado->celular?></p>
                </div>
            

            </div>

        </div>
            
    </section>
    <section class="dashboard__contenedor" id="contenedorRegistrados">
        <div class="registrado">
            <h4 class="registrado__nombre">Estrato </h4>
            <div class="registrado__contenedor">
                <div class="registrado__datos">
                    <span class="registrado__label">estrato </span>
                    <p class="registrado__dato"><?php echo $registrado->estrato->estrato?></p>
                </div>
                <div class="registrado__datos">
                    <span class="registrado__label">Tarifa Plena</span>
                    <p class="registrado__dato">$<?php  echo $registrado->estrato->tarifa_plena?></p>
                </div>
            
                <div class="registrado__datos">
                    <span class="registrado__label">Subsidio</span>
                    <p class="registrado__dato">$<?php  echo $registrado->estrato->subsidio?></p>
                </div>
                <div class="registrado__datos">
                    <span class="registrado__label">Valor Mensual</span>
                    <p class="registrado__dato">$<?php  echo $registrado->estrato->copago?></p>
                </div>
            
        
            </div>
        </div>
            
    </section>
</div>

<section class="dashboard__contenedor" id="contenedorRegistrados">
    <div class="registrado">
        <h4 class="registrado__nombre">Observaciones </h4>
        <div class="registrado__contenedor">
        
            <div class="deuda">
                <span class="deuda__descripcion">Estado Actual </span>
                <p class="deuda__valor deuda__valor--<?php echo $registrado->estado==1?'pagado':'vencido'?>"><?php echo $registrado->estado==1?'Facturando':'Suspendido'?></p>
            </div>
            <div class="deuda">
                <span class="deuda__descripcion">Total Deuda a la Fecha </span>
                <p id="deuda" class="deuda__valor"></p>
            </div>
        </div>
           

    </div>
        
</section>

<main class="facturacion">
    <div class="facturacion__facturas">
        <div class="dashboard__contenedor" >
            
            <h4 class="">Facturas </h4>
            <div class="facturas" id="facturas">
                
            </div>
            
                
        </div>
    </div>
 
    <div class="facturacion__acciones">
        <div class="dashboard__contenedor">

            <h4 class="">Acciones </h4>
           
            <div class="actions registrado" id="actions">
                
              
            
            </div>
            
                
        </div>
    </div>
   
</main>

<section class="pagados" id="pagados">
    <div class="pagados__facturas">
        <div class="dashboard__contenedor" >
            
            <h4 class="">Pagos </h4>
            <div class="pagos" id="pagos">
                
            </div>
            
                
        </div>
    </div>
 
    <div class="pagados__acciones">
        <div class="dashboard__contenedor">

            <h4 class="">Información </h4>
           
            <div class="actions registrado" id="payments">
                
              
            
            </div>
            
                
        </div>
    </div>
   
</section>





