<?php
use Psr\Http\Message\ ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

//$verison is the database migration version.  bump up for any changes to schema
$version = 1;
/////////////
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$app = new \Slim\App([
    "settings" => [
        "displayErrorDetails" => true
    ]
]);

$container = $app->getContainer();
$container['version'] = $version;

require __DIR__ . "/config/logger.php";
require __DIR__ . "/config/handlers.php";
require __DIR__ . "/config/middleware.php";

//need to get client info prior to setting up database container
require __DIR__ . "/config/database.php";

$app->get("/", function ($request, $response, $arguments) {
  $this->spot->mapper("App\Models\Config")->migrate();
  print "This is Komodo API";
});



require __DIR__ . "/routes/token.php";
require __DIR__ . "/routes/upload.php";

$app->run();