<h2 class="dashboard__heading"><?php echo $titulo?></h2>
<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton" href="/admin/emitidas/todas">
        <i class="fa-solid fa-circle-plus"></i>
        Ver todas
    </a>
</div>

<div class="dashboard__contenedor" id="contenedorEmitidasPendientes">
    <?php //include_once __DIR__.'/../../templates/alertas.php'?>

        <table class="display responsive table" id="tablaEmitidasPendientes">
            <thead  >
                <tr>
                    <th>#</th>    
                    <th>Factura</th>
                    <th>Subscriptor</th>
                    <th>Estrato</th>
                    <th>Mes Facturado</th>
                    <th>monto</th>
                    <th>ver Factura</th>
                </tr>
            </thead>
           
        </table>
   
</div>

