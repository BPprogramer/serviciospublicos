<?php

require_once __DIR__ . '/../includes/app.php';

use Controllers\ApiCajas;
use Controllers\ApiEstratos;
use Controllers\ApiFacturas;
use Controllers\ApiPagos;
use Controllers\ApiRegistrados;
use Controllers\ApiUsuarios;
use Controllers\ApiCajasPagos;
use Controllers\ApiCuentasPorPagar;
use Controllers\ApiEmitidas;
use Controllers\ApiInicio;
use Controllers\ApiPagar;
use MVC\Router;
use Controllers\AuthController;
use Controllers\CajasController;
use Controllers\DashboardController;
use Controllers\EmitidasController;
use Controllers\UsuariosController;
use Controllers\EstratosController;
use Controllers\GeneraAutoController;
use Controllers\PagarController;
use Controllers\RegistradosController;
use Controllers\Contabilidad\TercerosController;
use Controllers\Contabilidad\CuentaBancariaController;
use Controllers\Contabilidad\EgresoSimpleController;
use Controllers\Contabilidad\ApiEgresosSimples;
use Controllers\Contabilidad\ApiConsignacion;


use Controllers\Contabilidad\ApiTerceros;
use Controllers\Contabilidad\CuentasController;
use Controllers\Contabilidad\ApiCuentas;
use Controllers\Contabilidad\BancoController;
use Controllers\Contabilidad\EgresoController;
use Controllers\Contabilidad\ConsignacionController;
use Controllers\Contabilidad\ApiBancos;
use Controllers\Contabilidad\ApiCuentaBancaria;
use Controllers\Contabilidad\ApiEgresos;
use Controllers\Contabilidad\ApiMovimientosCuenta;

$router = new Router();


// Login
$router->get('/', [AuthController::class, 'index']);
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

// Crear Cuenta
$router->get('/registro', [AuthController::class, 'registro']);
$router->post('/registro', [AuthController::class, 'registro']);




//area de administracion
$router->get('/admin/dashboard', [DashboardController::class, 'index']);

$router->get('/admin/pagos/pagar', [PagarController::class, 'index']);
$router->get('/admin/pagos', [PagarController::class, 'pagos']);


$router->get('/admin/registrados', [RegistradosController::class, 'index']);
$router->get('/admin/registrados/crear', [RegistradosController::class, 'crear']);
$router->post('/admin/registrados/crear', [RegistradosController::class, 'crear']);
$router->get('/admin/registrados/editar', [RegistradosController::class, 'editar']);
$router->post('/admin/registrados/editar', [RegistradosController::class, 'editar']);
$router->get('/admin/registrados/registrado', [RegistradosController::class, 'registradoInfo']);



$router->get('/admin/usuarios', [UsuariosController::class, 'index']);
$router->get('/admin/usuarios/crear', [UsuariosController::class, 'crear']);
$router->post('/admin/usuarios/crear', [UsuariosController::class, 'crear']);
$router->get('/admin/usuarios/editar', [UsuariosController::class, 'editar']);
$router->post('/admin/usuarios/editar', [UsuariosController::class, 'editar']);



$router->get('/admin/estratos', [EstratosController::class, 'index']);
$router->get('/admin/estratos/crear', [EstratosController::class, 'crear']);
$router->post('/admin/estratos/crear', [EstratosController::class, 'crear']);
$router->get('/admin/estratos/editar', [EstratosController::class, 'editar']);
$router->post('/admin/estratos/editar', [EstratosController::class, 'editar']);
$router->post('/admin/estratos/eliminar', [EstratosController::class, 'eliminar']);


$router->get('/admin/emitidas', [EmitidasController::class, 'index']);

//cajas

$router->get('/admin/cajas', [CajasController::class, 'index']);
$router->get('/admin/cajas/abrir', [CajasController::class, 'abrir']);
$router->post('/admin/cajas/abrir', [CajasController::class, 'abrir']);
$router->get('/admin/cajas/pagos', [CajasController::class, 'cajasPagos']);


$router->get('/api/inicio/registrados', [ApiInicio::class, 'registrados']);
$router->post('/api/inicio/fecha', [ApiInicio::class, 'fecha']);
$router->post('/api/inicio/ingresos-mensuales', [ApiInicio::class, 'ingresosMensuales']);
$router->post('/api/inicio/consignaciones', [ApiInicio::class, 'consignaciones']);

$router->get('/api/facturas', [ApiFacturas::class, 'facturas']);


$router->get('/api/cuentas-por-cobrar', [ApiCuentasPorPagar::class, 'cuentasPorCobrar']);

$router->get('/api/facturas-por-pagar', [ApiPagar::class, 'facturasPorPagar']);
$router->post('/api/subir-pagos', [ApiPagar::class, 'pagar']);
$router->get('/api/lista-pagos', [ApiPagar::class, 'pagos']);

$router->get('/api/emitidas', [ApiEmitidas::class, 'emitidasPendientes']);



$router->get('/api/registrados', [ApiRegistrados::class, 'index']);
$router->get('/api/registrados/eliminar', [ApiRegistrados::class, 'eliminar']);
$router->get('/api/registrados/info', [ApiRegistrados::class, 'informacion']);
$router->get('/api/registrados/download', [ApiRegistrados::class, 'downloadXlsx']);




$router->get('/api/usuarios', [ApiUsuarios::class, 'index']);
$router->get('/api/usuarios/eliminar', [ApiUsuarios::class, 'eliminar']);
$router->get('/api/usuarios/editar-estado', [ApiUsuarios::class, 'editar_estado']);

$router->get('/api/estratos', [ApiEstratos::class, 'index']);
$router->get('/api/estratos-all', [ApiEstratos::class, 'estratos']);
$router->get('/api/estratos/eliminar', [ApiEstratos::class, 'eliminar']);
$router->get('/api/estratos/info', [ApiEstratos::class, 'informacion']);

$router->get('/api/cajas', [ApiCajas::class, 'index']);
$router->post('/api/cajas/cerrar', [ApiCajas::class, 'cerrar']);
$router->get('/api/cajas/pagos', [ApiCajasPagos::class, 'informacionCaja']);


$router->get('/api/generar-facturas', [ApiFacturas::class, 'generarFacturas']);
$router->get('/api/generar-facturas-manual', [ApiFacturas::class, 'generarFacturasManual']);

$router->post('/api/facturas-registrado', [ApiFacturas::class, 'facturasRegistrado']);
$router->get('/api/previsualizar-factura', [ApiFacturas::class, 'previsualizarFactura']);
$router->get('/api/previsualizar-factura', [ApiFacturas::class, 'previsualizarFactura']);
$router->get('/api/eliminar-facturas', [ApiFacturas::class, 'eliminarFacturas']);

$router->get('/api/generar-auto', [GeneraAutoController::class, 'generarAuto']);
$router->post('/api/generar-auto', [GeneraAutoController::class, 'generarAuto']);

$router->post('/api/pagar', [ApiPagos::class, 'pagar']);
$router->post('/api/pagos', [ApiPagos::class, 'pagosRegistrado']);
$router->get('/api/previsualizar-pago', [ApiPagos::class, 'previsualizarPago']);
$router->post('/api/anular-pago', [ApiPagos::class, 'anularPago']);


//area pupblica
$router->get('/admin/contabilidad/terceros', [TercerosController::class, 'index']);
$router->get('/admin/contabilidad/terceros/crear', [TercerosController::class, 'crear']);
$router->post('/admin/contabilidad/terceros/crear', [TercerosController::class, 'crear']);
$router->get('/admin/contabilidad/terceros/editar', [TercerosController::class, 'editar']);
$router->post('/admin/contabilidad/terceros/editar', [TercerosController::class, 'editar']);
$router->get('/admin/contabilidad/terceros/ver', [TercerosController::class, 'ver']);

$router->get('/api/contabilidad/terceros/eliminar', [TercerosController::class, 'eliminar']);



$router->get('/api/terceros', [ApiTerceros::class, 'index']);
$router->get('/api/terceros/eliminar', [ApiTerceros::class, 'eliminar']);


// ==============================
// AREA PUBLICA - CUENTAS
// ==============================

$router->get('/admin/contabilidad/cuentas', [CuentasController::class, 'index']);

$router->get('/admin/contabilidad/cuentas/crear', [CuentasController::class, 'crear']);
$router->post('/admin/contabilidad/cuentas/crear', [CuentasController::class, 'crear']);

$router->get('/admin/contabilidad/cuentas/editar', [CuentasController::class, 'editar']);
$router->post('/admin/contabilidad/cuentas/editar', [CuentasController::class, 'editar']);

$router->get('/admin/contabilidad/cuentas/ver', [CuentasController::class, 'ver']);


// ==============================
// API CUENTAS
// ==============================



$router->get('/api/contabilidad/cuentas', [ApiCuentas::class, 'index']);
$router->get('/api/contabilidad/cuentas/eliminar', [ApiCuentas::class, 'eliminar']);


// AREA PUBLICA - BANCOS
// ==============================

$router->get('/admin/contabilidad/bancos', [BancoController::class, 'index']);

$router->get('/admin/contabilidad/bancos/crear', [BancoController::class, 'crear']);
$router->post('/admin/contabilidad/bancos/crear', [BancoController::class, 'crear']);

$router->get('/admin/contabilidad/bancos/editar', [BancoController::class, 'editar']);
$router->post('/admin/contabilidad/bancos/editar', [BancoController::class, 'editar']);

$router->get('/admin/contabilidad/bancos/ver', [BancoController::class, 'ver']);


// ==============================
// API BANCOS
// ==============================



$router->get('/api/contabilidad/bancos', [ApiBancos::class, 'index']);
$router->get('/api/contabilidad/bancos/eliminar', [ApiBancos::class, 'eliminar']);


// ==============================
// AREA PUBLICA - CUENTAS BANCARIAS
// ==============================

$router->get('/admin/contabilidad/cuentas-bancarias', [CuentaBancariaController::class, 'index']);
$router->get('/admin/contabilidad/cuentas-bancarias/crear', [CuentaBancariaController::class, 'crear']);
$router->post('/admin/contabilidad/cuentas-bancarias/crear', [CuentaBancariaController::class, 'crear']);
$router->get('/admin/contabilidad/cuentas-bancarias/editar', [CuentaBancariaController::class, 'editar']);
$router->post('/admin/contabilidad/cuentas-bancarias/editar', [CuentaBancariaController::class, 'editar']);
$router->get('/admin/contabilidad/cuentas-bancarias/ver', [CuentaBancariaController::class, 'ver']);

// ==============================
// API CUENTAS BANCARIAS
// ==============================

$router->get('/api/contabilidad/cuentas-bancarias', [ApiCuentaBancaria::class, 'index']);
$router->get('/api/contabilidad/cuentas-bancarias/eliminar', [ApiCuentaBancaria::class, 'eliminar']);



// ==============================
// AREA PUBLICA - EGRESOS
// ==============================

$router->get('/admin/contabilidad/egresos', [EgresoController::class, 'index']);
$router->get('/admin/contabilidad/egresos/crear', [EgresoController::class, 'crear']);
$router->post('/admin/contabilidad/egresos/crear', [EgresoController::class, 'crear']);
$router->get('/admin/contabilidad/egresos/editar', [EgresoController::class, 'editar']);
$router->post('/admin/contabilidad/egresos/editar', [EgresoController::class, 'editar']);
$router->get('/admin/contabilidad/egresos/ver', [EgresoController::class, 'ver']);

// ==============================
// API EGRESOS
// ==============================

$router->get('/api/contabilidad/egresos', [ApiEgresos::class, 'index']);
$router->post('/api/contabilidad/egresos/anular', [ApiEgresos::class, 'anular']);
$router->get('/api/contabilidad/egresos/imprimir', [ApiEgresos::class, 'imprimir']);

// ==============================
// AREA PUBLICA - EGRESOS SIMPLES
// ==============================

$router->get('/admin/contabilidad/egresos-simples', [EgresoSimpleController::class, 'index']);

$router->get('/admin/contabilidad/egresos-simples/crear', [EgresoSimpleController::class, 'crear']);
$router->post('/admin/contabilidad/egresos-simples/crear', [EgresoSimpleController::class, 'crear']);

$router->get('/admin/contabilidad/egresos-simples/editar', [EgresoSimpleController::class, 'editar']);
$router->post('/admin/contabilidad/egresos-simples/editar', [EgresoSimpleController::class, 'editar']);

$router->get('/admin/contabilidad/egresos-simples/ver', [EgresoSimpleController::class, 'ver']);


// ==============================
// API EGRESOS SIMPLES
// ==============================

$router->get('/api/contabilidad/egresos-simples', [ApiEgresosSimples::class, 'index']);
$router->post('/api/contabilidad/egresos-simples/anular', [ApiEgresosSimples::class, 'anular']);

// ==============================
// AREA PUBLICA - CONSIGNACIONES
// ==============================

$router->get('/admin/contabilidad/consignaciones', [ConsignacionController::class, 'index']);

$router->get('/admin/contabilidad/consignaciones/crear', [ConsignacionController::class, 'crear']);
$router->post('/admin/contabilidad/consignaciones/crear', [ConsignacionController::class, 'crear']);

$router->get('/admin/contabilidad/consignaciones/editar', [ConsignacionController::class, 'editar']);
$router->post('/admin/contabilidad/consignaciones/editar', [ConsignacionController::class, 'editar']);

$router->get('/admin/contabilidad/consignaciones/ver', [ConsignacionController::class, 'ver']);


// ==============================
// API CONSIGNACIONES
// ==============================

$router->get('/api/contabilidad/consignaciones', [ApiConsignacion::class, 'index']);
$router->post('/api/contabilidad/consignaciones/anular', [ApiConsignacion::class, 'anular']);


// ==============================
// API MOVIMIENTOS CUENTA BANCARIA
// ==============================

$router->get('/api/contabilidad/movimientos/consignaciones', [ApiMovimientosCuenta::class, 'consignaciones']);
$router->get('/api/contabilidad/movimientos/egresos', [ApiMovimientosCuenta::class, 'egresos']);
$router->get('/api/contabilidad/movimientos/egresos-simples', [ApiMovimientosCuenta::class, 'egresosSimples']);


$router->comprobarRutas();
