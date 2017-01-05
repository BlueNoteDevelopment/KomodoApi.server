<?php

$container = $app->getContainer();

$container["spot"] = function ($container) {
    //$databasename,$user ='',$password=''
    
    $databasename = $container['clientinfo']->database;
    $user = $container['clientinfo']->user;
    $password = $container['clientinfo']->password;
    
    if($databasename===''){
        throw new Exception('Database information was not supplied');
    }
    
    $config = new \Spot\Config();

    $mysql = $config->addConnection("mysql", [
        "dbname" => $databasename,
        "user" => $user==='' ? getenv("DB_USER") : $user,
        "password" => $password==='' ? getenv("DB_PASSWORD") : $password,
        "host" => getenv("DB_HOST"),
        "driver" => "pdo_mysql",
        "charset" => "utf8"
    ]);

    $spot = new \Spot\Locator($config);

    $logger = new Doctrine\DBAL\Logging\MonologSQLLogger($container["logger"]);
    $mysql->getConfiguration()->setSQLLogger($logger);

    return $spot;
};

/* old code for reference
   $container["spot"] = function ($container) {

    $config = new \Spot\Config();

    $mysql = $config->addConnection("mysql", [
        "dbname" => getenv("DB_NAME"),
        "user" => getenv("DB_USER"),
        "password" => getenv("DB_PASSWORD"),
        "host" => getenv("DB_HOST"),
        "driver" => "pdo_mysql",
        "charset" => "utf8"
    ]);

    $spot = new \Spot\Locator($config);

    $logger = new Doctrine\DBAL\Logging\MonologSQLLogger($container["logger"]);
    $mysql->getConfiguration()->setSQLLogger($logger);

    return $spot;
};
 */