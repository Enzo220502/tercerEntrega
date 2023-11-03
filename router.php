<?php
require_once './db/config.php';
require_once './libs/router.php';
require_once './app/controllers/apiProductosController.php';
require_once './app/controllers/apiUsers.php';

$router = new Router();

    #                   endpoint      verbo        controller             método
    $router->addRoute(  'productos',  'GET',    'ProductosApiController', 'get');

    $router->addRoute('productos/:ID', 'GET', 'ProductosApiController', 'get');

    $router->addRoute('productos','POST','ProductosApiController','add');

    $router->addRoute('productos/:ID','PUT','ProductosApiController','update');
    
    $router->addRoute('productos/:ID','DELETE','ProductosApiController','delete');

    $router->addRoute('user/token', 'GET',    'UserApiController', 'getToken'   );




$router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);
?>