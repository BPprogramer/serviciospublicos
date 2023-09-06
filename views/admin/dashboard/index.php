<h2 class="dashboard__heading"><?php echo $titulo?></h2>
    
<div class="dashboard__contenedor-boton">
    <span class="dashboard__boton" id="btnImprimirFacturas" >
        <i class="fa-solid fa-print"></i>
        Imprimir Facturas
    </span>
</div>

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



