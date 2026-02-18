<aside class="dashboard__sidebar">
    <nav class="dashboard__menu">
        <a href="/admin/dashboard" class="dashboard__enlace <?php echo pagina_actual('/dashboard') ? 'dashboard__enlace--actual' : '' ?>">
            <i class="fa-solid fa-house dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Inicio
            </span>
        </a>
        <a href="/admin/pagos" class="dashboard__enlace <?php echo pagina_actual('/pagos') ? 'dashboard__enlace--actual' : '' ?>">
            <i class="fa-solid fa-sack-dollar dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Pagos
            </span>
        </a>
        <a href="/admin/emitidas" class="dashboard__enlace <?php echo pagina_actual('/emitidas') ? 'dashboard__enlace--actual' : '' ?>">
            <i class="fa-solid fa-file-invoice-dollar dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Emitidas
            </span>
        </a>
        <a href="/admin/registrados" class="dashboard__enlace <?php echo pagina_actual('/registrados') ? 'dashboard__enlace--actual' : '' ?>">
            <i class="fa-solid fa-users dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Registrados
            </span>
        </a>


        <a href="/admin/cajas" class="dashboard__enlace <?php echo pagina_actual('/cajas') ? 'dashboard__enlace--actual' : '' ?>">
            <i class="fa-solid fa-cash-register dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Cajas
            </span>
        </a>
        <a href="/admin/estratos" class="dashboard__enlace <?php echo pagina_actual('/estratos') ? 'dashboard__enlace--actual' : '' ?>">
            <i class="fa-solid fa-life-ring dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Estratos
            </span>
        </a>
        <a href="/admin/contabilidad/terceros" class="dashboard__enlace <?php echo pagina_actual('/contabilidad/terceros') ? 'dashboard__enlace--actual' : '' ?>">
            <i class="fa-solid fa-id-card dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Terceros
            </span>
        </a>
        <a href="/admin/contabilidad/cuentas" class="dashboard__enlace <?php echo pagina_actual('/contabilidad/cuentas') ? 'dashboard__enlace--actual' : '' ?>">
            <i class="fa-solid fa-book-open dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Cuentas
            </span>
        </a>
        <a href="/admin/contabilidad/cuentas-bancarias" class="dashboard__enlace <?php echo pagina_actual('/contabilidad/cuentas-bancarias') ? 'dashboard__enlace--actual' : '' ?>">
            <i class="fa-solid fa-money-check-dollar dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Cuentas Bancarias
            </span>
        </a>
        <a href="/admin/contabilidad/consignaciones"
            class="dashboard__enlace <?php echo pagina_actual('/contabilidad/consignaciones') ? 'dashboard__enlace--actual' : '' ?>">
            <i class="fa-solid fa-arrow-trend-up dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Consignaciones
            </span>
        </a>

        <a href="/admin/contabilidad/egresos" class="dashboard__enlace <?php echo pagina_actual('/contabilidad/egresos') ? 'dashboard__enlace--actual' : '' ?>">
            <i class="fa-solid fa-arrow-trend-down dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Egresos
            </span>
        </a>
        <a href="/admin/contabilidad/egresos-simples"
            class="dashboard__enlace <?php echo pagina_actual('/contabilidad/egresos-simples') ? 'dashboard__enlace--actual' : '' ?>">
            <i class="fa-solid fa-hand-holding-dollar dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Egresos Simples
            </span>
        </a>

        <a href="/admin/contabilidad/bancos" class="dashboard__enlace <?php echo pagina_actual('/contabilidad/bancos') ? 'dashboard__enlace--actual' : '' ?>">
            <i class="fa-solid fa-building-columns dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Bancos
            </span>
        </a>


        <a href="/admin/usuarios" class="dashboard__enlace <?php echo pagina_actual('/usuarios') ? 'dashboard__enlace--actual' : '' ?>">
            <i class="fa-solid fa-people-roof dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Usuarios
            </span>
        </a>






    </nav>
</aside>