<?php

use App\SimpleRoutePhp\Route;

//INICIO: HEADER
header("Access-Control-Allow-Origin: *"); //Qualquer site pode acessar
header("Content-Type: application/json; charset=UTF-8"); //tipo de retorno
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE"); // tipos de verbos http aceitos
header("Access-Control-Max-Age: 3600"); // Durabilidade Máxima de 1 hora
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); // Habilita alguma autorização
// header('Access-Control-Allow-Headers:  X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding'); // Habilita alguma autorização
//FIM: HEADER

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/src/Config.php";

$route = new Route(URL_BASE);

$route->namespace("App\\Controllers");

$route->get("/", function () {
    echo "oi";
});

$route->post("/login", "UserController:loginUser");
$route->post("/register", "UserController:registerUser");


$route->dispatch();

$error = $route->getError();


if ($error["error"]) {
    echo json_encode($error);
}
