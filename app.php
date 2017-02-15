<?php
use Psr\Http\Message\ ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Ramsey\Uuid;

require '../vendor/autoload.php';

//$verison is the database migration version.  bump up for any changes to schema
$version = 2;
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

//conditionally load routes
require __DIR__ . "/routes/upload.php";

if(preg_match('/api\/eventlog/' ,$_SERVER['REQUEST_URI'])){
    require __DIR__ . "/routes/eventlog.php";
}

if(preg_match('/api\/serviceaccount/' ,$_SERVER['REQUEST_URI'])){
    require __DIR__ . "/routes/serviceaccount.php";
}

if(preg_match('/api\/useraccount/' ,$_SERVER['REQUEST_URI'])){
    require __DIR__ . "/routes/useraccount.php";
}

if(preg_match('/api\/configuration/' ,$_SERVER['REQUEST_URI'])){
    require __DIR__ . "/routes/configuration.php";
}


$app->run();