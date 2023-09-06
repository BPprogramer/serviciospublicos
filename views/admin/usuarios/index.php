<h2 class="dashboard__heading"><?php echo $titulo?></h2>
<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton" href="/servicios/admin/usuarios/crear">
        <i class="fa-solid fa-circle-plus"></i>
        Añadir Usuario
    </a>
</div>

<div class="dashboard__contenedor" id="contenedorUsuarios">
    <?php include_once __DIR__.'/../../templates/alertas.php'?>

        <table class="display responsive table" id="tablaUsuarios">
            <thead  >
                <tr>
                    <th>#</th>    
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Estado</th>
                    <th>Roll</th>
                    <th>Acciones</th>
                </tr>
            </thead>
           
        </table>
   
</div>

