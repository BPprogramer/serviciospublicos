<h2 class="dashboard__heading"><?php echo $titulo ?></h2>



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
            <p class="cajas__descripcion">Inactivos</p>
            <span class="cajas__dato" id="subscriptoresInactivos"></span>
        </div>
        <div class="cajas__caja">
            <p class="cajas__descripcion">Pagos Vigentes</p>
            <span class="cajas__dato" id="pagosVigentes"></span>
        </div>
        <div class="cajas__caja">
            <p class="cajas__descripcion">Total Recaudos</p>
            <span class="cajas__dato" id="totalPagos"></span>
        </div>
        <div class="cajas__caja">
            <p class="cajas__descripcion">Total Consigaciones</p>
            <span class="cajas__dato" id="totalConsignaciones"></span>
        </div>
        <div class="cajas__caja">
            <div class="cajas__caja-contenedor">
                <p class="cajas__descripcion">Recaudos a la Fecha</p>
                <input type="date" class="cajas__fecha" id="fecha" max="<?php echo date('Y-m-d') ?>">
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
                                <option value="01" <?php echo date('m') == "01" ? 'selected' : '' ?>>Enero</option>
                                <option value="02" <?php echo date('m') == "02" ? 'selected' : '' ?>>Febrero</option>
                                <option value="03" <?php echo date('m') == "03" ? 'selected' : '' ?>>Marzo</option>
                                <option value="04" <?php echo date('m') == "04" ? 'selected' : '' ?>>Abril</option>
                                <option value="05" <?php echo date('m') == "05" ? 'selected' : '' ?>>Mayo</option>
                                <option value="06" <?php echo date('m') == "06" ? 'selected' : '' ?>>Junio</option>
                                <option value="07" <?php echo date('m') == "07" ? 'selected' : '' ?>>Julio</option>
                                <option value="08" <?php echo date('m') == "08" ? 'selected' : '' ?>>Agosto</option>
                                <option value="09" <?php echo date('m') == "09" ? 'selected' : '' ?>>Septiembre</option>
                                <option value="10" <?php echo date('m') == "10" ? 'selected' : '' ?>>Octubre</option>
                                <option value="11" <?php echo date('m') == "11" ? 'selected' : '' ?>>Noviembre</option>
                                <option value="12" <?php echo date('m') == "12" ? 'selected' : '' ?>>Diciembre</option>
                            </select>
                            <select class="formulario__select" name="" id="year">
                                <?php $year = date('Y') - 3;
                                for ($i = 0; $i <= 3; $i++) {
                                ?>
                                    <option value="<?php echo $year + $i ?>" <?php echo date('Y') == $year + $i ? 'selected' : '' ?>><?php echo $year + $i ?></option>

                                <?php } ?>
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
            <div class="inicio__eliminar-facturas" id="btnEliminarFacturas">
                <span class="inicio__boton inicio__boton--eliminar">
                    <i class="fa-solid fa-trash"></i>
                    Eliminar facturas del último mes
                </span>
            </div>

        </div>
        <div class="inicio__generar">
            <div class="inicio__boton-generar inicio__boton--generar" id="btnGenerarFacturas">
                <span class="inicio__boton">
                    <i class="fa-solid fa-file-invoice"></i>
                    Generar Facturas del último mes
                </span>
            </div>


        </div>


    </div>



</section>



<div class="cajas">
    <div class="cajas__grid">
        <div class="cajas__caja">
            <div class="cajas__caja-contenedor">
                <p class="cajas__descripcion">Filtrar Facturas</p>
                <form action="" class="formulario cajas__formulario">
                    <div class="formularo__campo">
                        <div class="formulario__grid cajas__formularario">

                            <select class="formulario__select formulario__select--estratos" name="" id="estratos">
                                <option value="0" disabled selected>--Seleccione--</option>
                            </select>

                        </div>

                    </div>

                </form>


            </div>


            <div class="dashboard__contenedor-boton">
                <span class="dashboard__boton" id="btnImprimirFacturasFiltradas">
                    <i class="fa-solid fa-print"></i>
                    Imprimir Facturas
                </span>
            </div>
        </div>
        <div class="cajas__caja">
            <div class="cajas__caja-contenedor">
                <p class="cajas__descripcion">Imprimir Facturas</p>


            </div>

            <div class="dashboard__contenedor-boton">
                <span class="dashboard__boton" id="btnImprimirFacturas">
                    <i class="fa-solid fa-print"></i>
                    Imprimir Facturas
                </span>
            </div>
        </div>
        <div class="cajas__caja">
            <div class="cajas__caja-contenedor">
                <p class="cajas__descripcion">Cuentas Por Cobrar</p>


            </div>

            <div class="dashboard__contenedor-boton">
                <span class="dashboard__boton" id="btnCuentasPorPagar">
                    <i class="fa-solid fa-print"></i>
                    cuentas por cobrar
                </span>
            </div>
         

        </div>

    </div>
</div>

<div class="cajas">
    <div class="cajas__caja">
        <div class="cajas__caja-contenedor">
            <p class="cajas__descripcion cajas__descripcion--center">Consignaciones</p>
            <div class="cajas__display">

                <div>
                    <p class="cajas__descripcion">Elija año y mes facturado</p>

                    <form action="" class="formulario cajas__formulario">
                        <div class="formularo__campo">
                            <div class="">
                                <select class="formulario__select formulario__select--columna" name="" id="year_consignacion">
                                    <?php $year = date('Y') - 3;
                                    for ($i = 0; $i <= 3; $i++) {
                                    ?>
                                        <option value="<?php echo $year + $i ?>" <?php echo date('Y') == $year + $i ? 'selected' : '' ?>><?php echo $year + $i ?></option>

                                    <?php } ?>
                                </select>

                                <select class="formulario__select formulario__select--columna" name="" id="mes_consignacion">
                                    <option disabled selected>--Seleccione mes facturado--</option>
                                    <option value="01">Enero</option>
                                    <option value="02">Febrero</option>
                                    <option value="03">Marzo</option>
                                    <option value="04">Abril</option>
                                    <option value="05">Mayo</option>
                                    <option value="06">Junio</option>
                                    <option value="07">Julio</option>
                                    <option value="08">Agosto</option>
                                    <option value="09">Septiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11">Noviembre</option>
                                    <option value="12">Diciembre</option>
                                </select>

                            </div>

                        </div>

                    </form>
                </div>




                <div class="cajas__info">
                    <p class="cajas__descripcion">Aseo: <span class="cajas__dato" id="consignacionAseo">$0</span></p>
                    <p class="cajas__descripcion">Acueducto: <span class="cajas__dato" id="consignacionAlc">$0</span></p>
                    <p class="cajas__descripcion">Alcantarillado: <span class="cajas__dato" id="consignacionAcu">$0</span></p>
                </div>

                <div class="cajas__info">
                    <p class="cajas__descripcion">Total: <span class="cajas__dato" id="total_del_mes">$0</span></p>
                    <p class="cajas__descripcion">Ya Consignado: <span class="cajas__dato" id="ya_consignado">$0</span></p>
                    <p class="cajas__descripcion">Por Consignar: <span class="cajas__dato" id="a_consignar">$0</span></p>
                </div>

            </div>



        </div>


    </div>
</div>