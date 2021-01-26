<?php

use App\SimpleRoutePhp\Route;

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
