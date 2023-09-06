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
use Controllers\cajasController;
use Controllers\DashboardController;


use Controllers\UsuariosController;
use Controllers\EstratosController;
use Controllers\GeneraAutoController;
use Controllers\RegistradosController;
use Model\GeneraAuto;

$router = new Router();


// Login
$router->get('/', [AuthController::class, 'index']);
$router->get('/servicios/login', [AuthController::class, 'login']);
$router->post('/servicios/login', [AuthController::class, 'login']);
$router->post('servicios/logout', [AuthController::class, 'logout']);

// Crear Cuenta
$router->get('/servicios/registro', [AuthController::class, 'registro']);
$router->post('/servicios/registro', [AuthController::class, 'registro']);




//area de administracion
$router->get('/servicios/admin/dashboard', [DashboardController::class, 'index']);



$router->get('/servicios/admin/registrados', [RegistradosController::class, 'index']);
$router->get('/servicios/admin/registrados/crear', [RegistradosController::class, 'crear']);
$router->post('/servicios/admin/registrados/crear', [RegistradosController::class, 'crear']);
$router->get('/servicios/admin/registrados/editar', [RegistradosController::class, 'editar']);
$router->post('/servicios/admin/registrados/editar', [RegistradosController::class, 'editar']);
$router->get('/servicios/admin/registrados/registrado', [RegistradosController::class, 'registradoInfo']);



$router->get('/servicios/admin/usuarios', [UsuariosController::class, 'index']);
$router->get('/servicios/admin/usuarios/crear', [UsuariosController::class, 'crear']);
$router->post('/servicios/admin/usuarios/crear', [UsuariosController::class, 'crear']);
$router->get('/servicios/admin/usuarios/editar', [UsuariosController::class, 'editar']);
$router->post('/servicios/admin/usuarios/editar', [UsuariosController::class, 'editar']);



$router->get('/servicios/admin/estratos', [EstratosController::class, 'index']);
$router->get('/servicios/admin/estratos/crear', [EstratosController::class, 'crear']);
$router->post('/servicios/admin/estratos/crear', [EstratosController::class, 'crear']);
$router->get('/servicios/admin/estratos/editar', [EstratosController::class, 'editar']);
$router->post('/servicios/admin/estratos/editar', [EstratosController::class, 'editar']);
$router->post('/servicios/admin/estratos/eliminar', [EstratosController::class, 'eliminar']);

//cajas

$router->get('/servicios/admin/cajas', [CajasController::class, 'index']);
$router->get('/servicios/admin/cajas/abrir', [CajasController::class, 'abrir']);
$router->post('/servicios/admin/cajas/abrir', [CajasController::class, 'abrir']);
$router->get('/servicios/admin/cajas/pagos', [CajasController::class, 'cajasPagos']);


$router->get('/servicios/api/facturas',[ApiFacturas::class, 'facturas']);

$router->get('/servicios/api/registrados',[ApiRegistrados::class, 'index'] );
$router->get('/servicios/api/registrados/eliminar',[ApiRegistrados::class, 'eliminar'] );
$router->get('/servicios/api/registrados/info',[ApiRegistrados::class, 'informacion'] );


$router->get('/servicios/api/usuarios',[ApiUsuarios::class, 'index'] );
$router->get('/servicios/api/usuarios/eliminar',[ApiUsuarios::class, 'eliminar'] );
$router->get('/api/usuarios/editar-estado',[ApiUsuarios::class, 'editar_estado'] );

$router->get('/servicios/api/estratos',[ApiEstratos::class, 'index'] );
$router->get('/servicios/api/estratos/eliminar',[ApiEstratos::class, 'eliminar'] );
$router->get('/servicios/api/estratos/info',[ApiEstratos::class, 'informacion'] );

$router->get('/servicios/api/cajas',[ApiCajas::class, 'index'] );
$router->post('/servicios/api/cajas/cerrar',[ApiCajas::class, 'cerrar'] );
$router->get('/servicios/api/cajas/pagos',[ApiCajasPagos::class, 'informacionCaja'] );


$router->get('/servicios/api/generar-facturas', [ApiFacturas::class, 'generarFacturas']);
$router->get('/servicios/api/generar-facturas-manual', [ApiFacturas::class, 'generarFacturasManual']);

$router->post('/servicios/api/facturas-registrado', [ApiFacturas::class, 'facturasRegistrado']);
$router->get('/servicios/api/previsualizar-factura', [ApiFacturas::class, 'previsualizarFactura']);
$router->get('/servicios/api/previsualizar-factura', [ApiFacturas::class, 'previsualizarFactura']);
$router->get('/servicios/api/eliminar-facturas', [ApiFacturas::class, 'eliminarFacturas']);

$router->get('/servicios/api/generar-auto', [GeneraAutoController::class, 'generarAuto']);
$router->post('/servicios/api/generar-auto', [GeneraAutoController::class, 'generarAuto']);

$router->post('/servicios/api/pagar', [ApiPagos::class, 'pagar']);
$router->post('/servicios/api/pagos', [ApiPagos::class, 'pagosRegistrado']);
$router->get('/servicios/api/previsualizar-pago', [ApiPagos::class, 'previsualizarPago']);


//area pupblica
//$router->get('/',[PaginasController::class, 'index']);
$router->get('/servicios/devwebcamp',[PaginasController::class, 'evento']);
$router->get('/servicios/paquetes',[PaginasController::class, 'paquetes']);
$router->get('/servicios/workshops-conferencias',[PaginasController::class, 'conferencias']);

$router->comprobarRutas();