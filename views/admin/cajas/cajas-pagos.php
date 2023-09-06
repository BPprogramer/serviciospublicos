
<h2 class="dashboard__heading"><?php echo $titulo?></h2>

<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton" href="/admin/cajas">
        <i class="fa-solid fa-arrow-left"></i>
        Volver
    </a>
</div>

<div class="flexbox">
    <section class="dashboard__contenedor" id="contenedorRegistrados">
        <div class="registrado">
            <div class="registrado_titulo">
                
            </div>
            <h4 class="registrado__nombre">Información de la Caja </h4>
            <div class="registrado__contenedor">
                <div class="registrado__datos">
                    <span class="registrado__label">Nombre </span>
                  
                    <p class="registrado__dato" id="cliente"><?php echo $caja->responsable?></p>
                </div>
                <div class="registrado__datos">
                    <span class="registrado__label">Fecha de apertura</span>
                    <p class="registrado__dato"><?php echo ($caja->fecha_apertura)?></p>
                </div>
                <div class="registrado__datos">
                    <span class="registrado__label">Fecha de Cierre</span>
                    <p class="registrado__dato"><?php echo $caja->fecha_cierre=='2000-01-01 01:01:01'?'':$caja->fecha_cierre?></p>
                </div>
                <div class="registrado__datos">
                    <span class="registrado__label">Efectivo Inicial</span>
                    <p class="registrado__dato"><?php echo '$'.number_format($caja->efectivo_inicial)?></p>
                </div>
            
               
            

            </div>

        </div>
            
    </section>
    <section class="dashboard__contenedor" id="contenedorRegistrados">
        <div class="registrado">
            <h4 class="registrado__nombre">Info </h4>
            <div class="registrado__contenedor">
                <div class="registrado__datos">
                    <span class="registrado__label">Recaudo </span>
                    <p class="registrado__dato"><?php echo number_format($caja->total_recaudo)?></p>
                </div>
                <div class="registrado__datos">
                    <span class="registrado__label">Efectivo en Caja </span>
                    <p class="registrado__dato"><?php echo number_format($caja->total_efectivo)?></p>
                </div>
                <div class="registrado__datos">
                    <span class="registrado__label">transferencias </span>
                    <p class="registrado__dato"><?php echo number_format($caja->total_transferencias)?></p>
                </div>
         
                <div class="registrado__datos">
                    <span class="registrado__label">estado </span>
                    <p class="registrado__dato registrado__dato--<?php echo $caja->estado==1?'activo':'neutro'?>"><?php echo $caja->estado==1?'Abierta':'Cerrada'?></p>
                </div>
        
            
        
            </div>
        </div>
            
    </section>
</div>

<div class="dashboard__contenedor" id="contenedorCajasPagas">


        <table class="display responsive table" id="tablaCajasPagos">
            <thead  >
                <tr>
                    <th>#</th>    
                    <th>Recaudador</th>
                    <th>Operación</th>
                    <th>Monto</th>
                    <th>Metodo</th>
                    <th>Subscriptor</th>
                    <th>Fecha</th>
                  
                </tr>
            </thead>
           
        </table>
   
</div>
