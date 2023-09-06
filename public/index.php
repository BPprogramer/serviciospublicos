<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\ApiCajas;
use Controllers\ApiEstratos;
use Controllers\ApiFacturas;
use Controllers\ApiPagos;
use Controllers\ApiRegistrados;
use Controllers\ApiUsuarios;
use Controllers\ApiCajasPagos;
use MVC\Router;
use Controllers\AuthController;
use Controllers\CajasController;
use Controllers\DashboardController;


use Controllers\UsuariosController;
use Controllers\EstratosController;
use Controllers\GeneraAutoController;
use Controllers\RegistradosController;
use Model\GeneraAuto;

$router = new Router();


// Login
$router->get('/', [AuthController::class, 'index']);
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('servicios/logout', [AuthController::class, 'logout']);

// Crear Cuenta
$router->get('/registro', [AuthController::class, 'registro']);
$router->post('/registro', [AuthController::class, 'registro']);




//area de administracion
$router->get('/admin/dashboard', [DashboardController::class, 'index']);



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

//cajas

$router->get('/admin/cajas', [CajasController::class, 'index']);
$router->get('/admin/cajas/abrir', [CajasController::class, 'abrir']);
$router->post('/admin/cajas/abrir', [CajasController::class, 'abrir']);
$router->get('/admin/cajas/pagos', [CajasController::class, 'cajasPagos']);


$router->get('/api/facturas',[ApiFacturas::class, 'facturas']);

$router->get('/api/registrados',[ApiRegistrados::class, 'index'] );
$router->get('/api/registrados/eliminar',[ApiRegistrados::class, 'eliminar'] );
$router->get('/api/registrados/info',[ApiRegistrados::class, 'informacion'] );


$router->get('/api/usuarios',[ApiUsuarios::class, 'index'] );
$router->get('/api/usuarios/eliminar',[ApiUsuarios::class, 'eliminar'] );
$router->get('/api/usuarios/editar-estado',[ApiUsuarios::class, 'editar_estado'] );

$router->get('/api/estratos',[ApiEstratos::class, 'index'] );
$router->get('/api/estratos/eliminar',[ApiEstratos::class, 'eliminar'] );
$router->get('/api/estratos/info',[ApiEstratos::class, 'informacion'] );

$router->get('/api/cajas',[ApiCajas::class, 'index'] );
$router->post('/api/cajas/cerrar',[ApiCajas::class, 'cerrar'] );
$router->get('/api/cajas/pagos',[ApiCajasPagos::class, 'informacionCaja'] );


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


//area pupblica
//$router->get('/',[PaginasController::class, 'index']);
$router->get('/devwebcamp',[PaginasController::class, 'evento']);
$router->get('/paquetes',[PaginasController::class, 'paquetes']);
$router->get('/workshops-conferencias',[PaginasController::class, 'conferencias']);

$router->comprobarRutas();