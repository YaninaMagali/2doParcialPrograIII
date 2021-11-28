<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/DAO.php';
require_once './middlewares/ValidadorMdw.php';

require_once './controllers/LoginController.php';
require_once './controllers/CabaniaController.php';
require_once './controllers/AlquilerController.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes
$app->group('/cabania', function (RouteCollectorProxy $group) {
$group->get('[/]', \CabaniaController::class . ':ListarCabanias');
$group->get('/capacidad/{cantidad_personas}', \CabaniaController::class . ':ListarCabaniasPorCapacidad');
$group->get('/capacidad/id/{id}', \CabaniaController::class . ':ListarCabaniasPorId')->add(\ValidadorMdw::class . ':ValidarEsUsuarioRegistrado');
$group->post('[/]', \CabaniaController::class . ':CrearCabania')->add(\ValidadorMdw::class . ':ValidarEsAdmin');
$group->delete('/{id}', \CabaniaController::class . ':EliminarCabania')->add(\ValidadorMdw::class . ':ValidarEsAdmin');
$group->put('/{id}', \CabaniaController::class . ':ModificarCabania')->add(\ValidadorMdw::class . ':ValidarEsAdmin');
});

  $app->group('/login', function (RouteCollectorProxy $group) {
    $group->post('[/]', \LoginController::class . ':ValidarCredenciales');
    $group->post('/validarJWT', \LoginController::class . ':ValidarPerfilToken');
  });


  $app->group('/alquiler', function (RouteCollectorProxy $group) {
    $group->post('[/]', \AlquilerController::class . ':CrearAlquiler')->add(\ValidadorMdw::class . ':ValidarEsUsuarioRegistrado');
    $group->get('/fechas/{f1}/{f2}/{estilo}', \AlquilerController::class . ':ListarAlquilerPorFechaEstilo')->add(\ValidadorMdw::class . ':ValidarEsAdmin');
    $group->get('/estilo/{estilo}', \AlquilerController::class . ':ListarUsuariosAlquilerPorEstilo')->add(\ValidadorMdw::class . ':ValidarEsAdmin');
    $group->get('/pdf/{f1}/{f2}', \AlquilerController::class . ':ListarAlquileresPdf');
  });

$app->run();