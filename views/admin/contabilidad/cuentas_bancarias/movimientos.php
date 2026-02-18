<div class="movimientos" id="movimientosCuenta" data-cuenta-id="<?php echo $cuenta->id; ?>">

    <!-- ============================= -->
    <!-- FILTRO POR FECHA -->
    <!-- ============================= -->

    <div style="margin-bottom:20px;padding:20px;background:#ffffff;border-radius:10px;">

        <h3 style="margin-bottom:15px;">Movimientos</h3>

        <div style="display:flex;gap:20px;flex-wrap:wrap;align-items:end;">

            <div>
                <label>Fecha Inicial</label><br>
                <input type="date" id="fecha_inicio"
                    value="<?php echo date('Y-m-d', strtotime('-1 month')); ?>">
            </div>

            <div>
                <label>Fecha Final</label><br>
                <input type="date" id="fecha_fin"
                    value="<?php echo date('Y-m-d'); ?>">
            </div>

            <div>
                <button id="btnFiltrarMovimientos"
                    style="padding:8px 15px;background:#0d6efd;color:white;border:none;border-radius:6px;">
                    Filtrar
                </button>
            </div>

        </div>

    </div>

    <!-- ============================= -->
    <!-- TABS -->
    <!-- ============================= -->

    <div class="tabs-movimientos">

        <div class="tabs-header" style="display:flex;border-bottom:2px solid #ddd;margin-bottom:15px;">
            <button class="tab-btn active" data-tab="tab-consignaciones">Consignaciones</button>
            <button class="tab-btn" data-tab="tab-egresos">Egresos</button>
            <button class="tab-btn" data-tab="tab-egresos-simples">Egresos Simples</button>
        </div>

        <div class="tabs-body">

            <!-- TAB CONSIGNACIONES -->
            <div id="tab-consignaciones" class="tab-content active">
                <table id="tablaConsignacionesMov" class="display responsive table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Monto</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <!-- TAB EGRESOS -->
            <div id="tab-egresos" class="tab-content">
                <table id="tablaEgresosMov" class="display responsive table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Fecha</th>

                            <th>Consecutivo</th>
                       
                            <th>Detalle</th>
                            <th>Estado</th>
                            <th>Debitos</th>
                            <th>Creditos</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <!-- TAB EGRESOS SIMPLES -->
            <div id="tab-egresos-simples" class="tab-content">
                <table id="tablaEgresosSimplesMov" class="display responsive table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Monto</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>

    </div>

</div>

<script src="/build/js/movimientos.js"></script>