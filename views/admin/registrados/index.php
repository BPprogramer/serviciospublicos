<h2 class="dashboard__heading"><?php echo $titulo?></h2>
<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton" href="/admin/registrados/crear">
        <i class="fa-solid fa-circle-plus"></i>
        Añadir Suscriptor
    </a>
</div>

<div class="dashboard__contenedor" id="contenedorRegistrados">
    <?php //include_once __DIR__.'/../../templates/alertas.php'?>

        <table class="display responsive table" id="tablaRegistrados">
            <thead  >
                <tr>
                    <th>#</th>    
                    <th>Nombre</th>
                    <th>Cedula / Nit</th>
            
      
                    <th>Direccion</th>
                    <th>Estrato</th>
                    <th>estado</th>

                    <th>Acciones</th>
                </tr>
            </thead>
           
        </table>
   
</div>

