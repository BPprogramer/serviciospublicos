<h2 class="dashboard__heading"><?php echo $titulo?></h2>
<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton" href="/admin/pagos/pagar">
        <i class="fa-solid fa-circle-plus"></i>
        Subir Pagos
    </a>
</div>

<div class="dashboard__contenedor" id="contenedorPagos">
    <?php //include_once __DIR__.'/../../templates/alertas.php'?>

        <table class="display responsive table" id="tablaPagos">
            <thead  >
                <tr>
                    <th>#</th>    
                    <th>Comprobante</th>
                    <th>Factura</th>
                    <th>Subscriptor</th>
                    <th>Fecha pago </th>
                    <th>monto</th>
                    <th>Recaudador</th>
                    <th>estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
           
        </table>
   
</div>

