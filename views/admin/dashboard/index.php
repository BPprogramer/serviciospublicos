<h2 class="dashboard__heading"><?php echo $titulo?></h2>
    


<section class="cajas" id="cajas">
    <div class="cajas__grid">
        <div class="cajas__caja">
            <p class="cajas__descripcion">Total de Subscriptores</p>
            <span class="cajas__dato" id="subscriptores"></span>
        </div>
       

        <div class="cajas__caja">
            <p class="cajas__descripcion">Usuarios Vigentes</p>
            <span class="cajas__dato" id="subscriptoresVigentes"></span>
        </div>

        <div class="cajas__caja">
            <p class="cajas__descripcion" >Inactivos</p>
            <span class="cajas__dato" id="subscriptoresInactivos"></span>
        </div>
        <div class="cajas__caja">
            <p class="cajas__descripcion" >Pagos Vigentes</p>
            <span class="cajas__dato" id="pagosVigentes"></span>
        </div>
        <div class="cajas__caja">
            <p class="cajas__descripcion" >Total Recaudos</p>
            <span class="cajas__dato" id="totalPagos"></span>
        </div>
        <div class="cajas__caja">
            <div class="cajas__caja-contenedor">
                <p class="cajas__descripcion">Recaudos a la Fecha</p>
                <input type="date" class="cajas__fecha" id="fecha" max="<?php echo date('Y-m-d')?>">
            </div>
         
            <span class="cajas__dato" id="ingreso">50</span>
        </div>
        <div class="cajas__caja">
            <div class="cajas__caja-contenedor">
                <p class="cajas__descripcion">Recaudos Mensuales</p>
                <form action="" class="formulario cajas__formulario">
                    <div class="formularo__campo">
                        <div class="formulario__grid cajas__formularario">
                  
                            <select class="formulario__select" name="" id="mes">
                                <option value="01" <?php echo date('m')=="01"?'selected':'' ?>>Enero</option>
                                <option value="02" <?php echo date('m')=="02"?'selected':'' ?>>Febrero</option>
                                <option value="03" <?php echo date('m')=="03"?'selected':'' ?>>Marzo</option>
                                <option value="04" <?php echo date('m')=="04"?'selected':'' ?>>Abril</option>
                                <option value="05" <?php echo date('m')=="05"?'selected':'' ?>>Mayo</option>
                                <option value="06" <?php echo date('m')=="06"?'selected':'' ?>>Junio</option>
                                <option value="07" <?php echo date('m')=="07"?'selected':'' ?>>Julio</option>
                                <option value="08" <?php echo date('m')=="08"?'selected':'' ?>>Agosto</option>
                                <option value="09" <?php echo date('m')=="09"?'selected':'' ?>>Septiembre</option>
                                <option value="10" <?php echo date('m')=="10"?'selected':'' ?>>Octubre</option>
                                <option value="11" <?php echo date('m')=="11"?'selected':'' ?>>Noviembre</option>
                                <option value="12" <?php echo date('m')=="12"?'selected':'' ?>>Diciembre</option>
                            </select>
                            <select class="formulario__select" name="" id="year">
                               <?php $year = date('Y')-3;
                                    for($i=0; $i<=3; $i++){
                                ?>
                                        <option  value="<?php echo $year+$i?>" <?php echo date('Y')==$year+$i?'selected':'' ?>><?php echo $year+$i?></option>
                                    
                                <?php }?>
                            </select>
                        </div>
          
                    </div>
                    
                </form>
               
               
            </div>
            
         
            <span class="cajas__dato" id="ingresosMensuales">50</span>
        </div>
    </div>
</section>



<section class="inicio">
    <div class="inicio__grid dashboard__contenedor">
        <div class="inicio__formulario ">

            <div class="inicio__boton-onoff">
                            
                <p class="inicio__texto-onoff">Facturacion automática</p>
                <label class="switch" for="checkbox" id="switch">
           <!--          <input type="checkbox" id="checkbox"/>
                    <div class="slider round"></div> -->
                </label>
        
            </div>
            <div class="inicio__eliminar-facturas" id="btnEliminarFacturas" >
                <span class="inicio__boton inicio__boton--eliminar" >
                    <i class="fa-solid fa-trash"></i>
                    Eliminar facturas del último mes
                </span>
            </div>

        </div>
        <div class="inicio__generar" >
            <div class="inicio__boton-generar inicio__boton--generar" id="btnGenerarFacturas">
                <span class="inicio__boton"  >
                    <i class="fa-solid fa-file-invoice"></i>
                    Generar Facturas del último mes
                </span>
            </div>
           
            
        </div>
        
        
    </div>

     

</section>

<div class="dashboard__contenedor-boton">
    <span class="dashboard__boton" id="btnImprimirFacturas" >
        <i class="fa-solid fa-print"></i>
        Imprimir Facturas
    </span>
</div>

