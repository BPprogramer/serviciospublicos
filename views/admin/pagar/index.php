<h2 class="dashboard__heading"><?php echo $titulo?></h2>


<div class="dashboard__contenedor" id="contenedorPagar">
 
        
       <div class="pagar">
            <div class="formulario__campo">
                <label for="numero_factura" class="formulario__label">Introduzca el Código o escanie el codigo de barras:</label>
                <input 
                    type="text"
                    id="numero_factura"
                    placeholder="Codigo"
                    value=""
                    >
            </div>
            <div class="informacion__contendorBtn">
                    <a href="/admin/pagos" class="informacion__btnPagar" id="">Volver</a>
            </div>
       </div>
      
         
    
    
    
</div>


<main class="facturacion">
    <div class="facturacion__facturas">
        <div class="dashboard__contenedor" >
            
            <h4 class="">Facturas a Pagar</h4>
            <div class="facturas" id="pagarFacturas">
                
            </div>
          
           
           <div class="informacion" id="infoPago">
                <div class="informacion__pago">
                    <span class="informacion__texto">cantidad de facturas</span>
                    <p class="informacion__info" id="totalFacturas">0</p>
                </div>
                <div class="informacion__pago">
                    <span class="informacion__texto">Total Recaudo</span>
                    <p class="informacion__info" id="totalRecaudo">$0</p>
                </div>
                <div class="informacion__contendorBtn">
                    <span class="informacion__btnPagar" id="guardarPagos">Guardar Pagos</span>
                </div>
             
            </div>
            
                
        </div>
    </div>

    
 
</main>

