<?php

use App\SimpleRoutePhp\Route;

//INICIO: HEADER
header("Access-Control-Allow-Origin: *"); //Qualquer site pode acessar
// header("Content-Type: application/json; charset=UTF-8"); //tipo de retorno
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE"); // tipos de verbos http aceitos
header("Access-Control-Max-Age: 3600"); // Durabilidade Máxima de 1 hora
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); // Habilita alguma autorização
// header('Access-Control-Allow-Headers:  X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding'); // Habilita alguma autorização
//FIM: HEADER

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/src/Config.php";

$route = new Route(URL_BASE);

$route->namespace("App\\Controllers");

$route->get("/", function ($data) {
    echo json_encode("oi");
});

$route->post("/login", "UserController:loginUser");
$route->post("/register", "UserController:registerUser");
$route->get("/tweet/{start}/{end}", "TweetController:getGlobalTweets");
$route->get("/comment/{id}/{start}/{end}", "TweetController:getComment");

$route->get("/{slug}", "UserController:getDataUser");

$route->middleware("\\App\\Helpers\\JWTWrapper:auth", function () use ($route) {

    $route->get("/teste/in", "UserController:testeIn");

    $route->put("/perfil", "UserController:setDataUser");
    $route->delete("/perfil", "UserController:deleteUser");
    $route->get("/perfil/tweet/{start}/{end}", "TweetController:getMyTweets");

    $route->get("/follow/{slug}", "UserController:followingUsers");
    $route->post("/follow/{id}", "UserController:followUser");

    $route->post("/tweet", "TweetController:setTweet");

    $route->get("/tweet/my/{start}/{end}", "TweetController:getPersonalTweets");

    $route->delete("/tweet/{id}", "TweetController:deleteTweet");



    $route->post("/comment/{id}", "TweetController:setComment");
});

$route->dispatch();

$error = $route->getError();

if ($error["error"]) {
    echo json_encode($error);
}
