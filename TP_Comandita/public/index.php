<?php

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Config\Database;
use App\Controllers\OrderDetailController;
use App\Controllers\EmpleadoController;
use App\Controllers\MenuController;
use App\Controllers\MesaController;
use App\Controllers\LogsController;
use App\Middleware\JsonMiddleware;
use App\Middleware\AdminPassMiddleware;
use App\Middleware\SocioPassMiddleware;


require __DIR__ . '/../vendor/autoload.php';

$conn = new Database;

$app = AppFactory::create();
$app->addErrorMiddleware(true, false, false);
$app->setBasePath('/TP_Comandita/public');

$app->group('', function (RouteCollectorProxy $group) {

    //Registro de un nuevo empleado
    $group->post('/users', EmpleadoController::class . ":addOne");
    //Login de usuario existente    
    $group->post('/login', EmpleadoController::class . ":login");
    //Revision de mesas disponibles
    $group->get('/mesa', MesaController::class . ":getDisponibles");
    //Revision de menu
    $group->get('/menu', MenuController::class . ":getAll");
    //Asigna las ordenes a cada sector
    $group->post('/order', OrderDetailController::class . ":asignOrder");
    //Verificacion de pedidos 
    $group->get('/pedidos', OrderDetailController::class . ":getDisponibles");
    //Toma de orden para preparar
    $group->post('/pedidos', OrderDetailController::class . ":takeOrder");
    //Finalizacion de orden
    $group->post('/pedidofinalizado', OrderDetailController::class . ":finishOrder");
    //Consulta de usuario por su pedido 
    $group->get('/pedidos/{id}', OrderDetailController::class . ":getPedido");
    //Se sirve la orden al cliente
    $group->post('/servir', OrderDetailController::class . ":serve");
    //Consulta de todos los pedidos para los socios 
    $group->get('/revision', OrderDetailController::class . ":getAll");
    //Cobro y recibo generado por el servicio 
    $group->post('/cobro', OrderDetailController::class . ":checkout")->add(new SocioPassMiddleware());
    //Devolucion del usuario en cuanto al servicio 
    $group->post('/review', OrderDetailController::class . ":review");
    //Visualización del admin de los accesos realizados 
    $group->get('/logs', LogsController::class . ":getAll")->add(new AdminPassMiddleware());
    //Habilita la mesa nuevamente
    $group->post('/habilitar', MesaController::class . ":UpdateOne")->add(new AdminPassMiddleware());
    //Baja física de un usuario
    $group->delete('/user/{id}', EmpleadoController::class . ":deleteOne")->add(new AdminPassMiddleware());
})->add(new JsonMiddleware);

$app->addBodyParsingMiddleware();
$app->run();
