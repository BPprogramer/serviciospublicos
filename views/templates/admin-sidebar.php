
<aside class="dashboard__sidebar">
    <nav class="dashboard__menu">
        <a href="/admin/dashboard" class="dashboard__enlace <?php echo pagina_actual('/dashboard') ? 'dashboard__enlace--actual':''?>">
            <i class="fa-solid fa-house dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Inicio
            </span>
        </a>
        <a href="/admin/pagos" class="dashboard__enlace <?php echo pagina_actual('/pagos') ? 'dashboard__enlace--actual':''?>">
            <i class="fa-solid fa-sack-dollar dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Pagos
            </span>
        </a>
        <a href="/admin/emitidas" class="dashboard__enlace <?php echo pagina_actual('/emitidas') ? 'dashboard__enlace--actual':''?>">
            <i class="fa-solid fa-file-invoice-dollar dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Emitidas
            </span>
        </a>
        <a href="/admin/registrados" class="dashboard__enlace <?php echo pagina_actual('/registrados') ? 'dashboard__enlace--actual':''?>">
            <i class="fa-solid fa-users dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Registrados
            </span>
        </a>
      
       
        <a href="/admin/cajas" class="dashboard__enlace <?php echo pagina_actual('/cajas') ? 'dashboard__enlace--actual':''?>">
            <i class="fa-solid fa-cash-register dashboard__icono" ></i>
            <span class="dashboard__menu-texto">
                Cajas
            </span>
        </a>
        <a href="/admin/estratos" class="dashboard__enlace <?php echo pagina_actual('/estratos') ? 'dashboard__enlace--actual':''?>">
            <i class="fa-solid fa-life-ring dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Estratos
            </span>
        </a>
        <a href="/admin/usuarios" class="dashboard__enlace <?php echo pagina_actual('/usuarios') ? 'dashboard__enlace--actual':''?>">
            <i class="fa-solid fa-people-roof dashboard__icono" ></i>
            <span class="dashboard__menu-texto">
                Usuarios
            </span>
        </a>
    
        
    
    </nav>
</aside>