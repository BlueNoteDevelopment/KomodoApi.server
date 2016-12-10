<?php
use Psr\Http\Message\ ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$app = new \Slim\App([
    "settings" => [
        "displayErrorDetails" => true
    ]
]);

require __DIR__ . "/config/logger.php";
require __DIR__ . "/config/handlers.php";
require __DIR__ . "/config/middleware.php";
require __DIR__ . "/config/database.php";

$app->get("/", function ($request, $response, $arguments) {
  $this->spot->mapper("App\Config")->migrate();
  print "This is Komodo API";
});

require __DIR__ . "/routes/token.php";

$app->run();