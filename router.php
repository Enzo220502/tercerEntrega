<?php
require_once './db/config.php';
require_once './libs/router.php';
require_once './app/controllers/apiProductosController.php';
require_once './app/controllers/apiUsersController.php';

$router = new Router();

    #                   endpoint      verbo        controller             método
    $router->addRoute(  'productos',  'GET',    'ProductosApiController', 'obtener');

    $router->addRoute('productos/:ID', 'GET', 'ProductosApiController', 'obtener');

    $router->addRoute('productos','POST','ProductosApiController','nuevo');

    $router->addRoute('productos/:ID','PUT','ProductosApiController','actualizar');
    
    $router->addRoute('productos/:ID','DELETE','ProductosApiController','borrar');

    $router->addRoute('user/token', 'GET',    'UserApiController', 'obtenerToken'   );




$router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);
?>