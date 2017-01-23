<?php

use Firebase\JWT\JWT;
use Tuupola\Base62;


$app->post("/auth/token", function ($request, $response, $arguments) {
    $requested_scopes = $request->getParsedBody();

    $valid_scopes = [
        "config.create",
        "config.read",
        "config.update",
        "config.delete",
        "config.list",
        "config.all"
    ];

    $scopes = array_filter($requested_scopes, function ($needle) use ($valid_scopes) {
        return in_array($needle, $valid_scopes);
    });

    $now = new DateTime();
    $future = new DateTime("now +24 hours");
    $server = $request->getServerParams();
    
    $jti = Base62::encode(random_bytes(16));
    
    //run authentcation function
    $authResult = App\Authentication::authenticateUser($server["PHP_AUTH_USER"], $server["PHP_AUTH_PW"],$this->repository);
    //if OK then return token else 403
    
    if($authResult->result){
        $payload = [
            "iat" => $now->getTimeStamp(),
            "exp" => $future->getTimeStamp(),
            "jti" => $jti,
            "sub" => $authResult,
            "scope" => $scopes
        ];

        $secret = getenv("JWT_SECRET");
        $token = JWT::encode($payload, $secret, "HS256");
        $data["status"] = "ok";
        $data["token"] = $token;

        return $response->withStatus(201)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));    
    }else{
        $error["error"] = "Invalid User";
        $error["code"] = "500";
        
        $response->withStatus(500)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($error, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));   
    }

});

$app->post("/auth/token/service", function ($request, $response, $arguments) {
    $data = $request->getParsedBody(); 
    
    $requested_scopes = $data['scopes'];

    $valid_scopes = [
        "config.create",
        "config.read",
        "config.update",
        "config.delete",
        "config.list",
        "config.all"
    ];

    $scopes = array_filter($requested_scopes, function ($needle) use ($valid_scopes) {
        return in_array($needle, $valid_scopes);
    });

    $now = new DateTime();
    $future = new DateTime("now +168 hours");
     
    $jti = Base62::encode(random_bytes(16));
    
    //run authentcation function
    $authResult = App\Authentication::authenticateService($data['servicehostname'],$this->repository);
    //if OK then return token else 403
    
    if($authResult->result){
        $payload = [
            "iat" => $now->getTimeStamp(),
            "exp" => $future->getTimeStamp(),
            "jti" => $jti,
            "sub" => $authResult,
            "scope" => $scopes
        ];

        $secret = getenv("JWT_SECRET");
        $token = JWT::encode($payload, $secret, "HS256");
        $data["status"] = "ok";
        $data["token"] = $token;

        return $response->withStatus(201)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));    
    }else{
        $error["error"] = "Invalid Service Account";
        $error["code"] = "500";
        
        $response->withStatus(500)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($error, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));   
    }

});


/* This is just for debugging, not usefull in real life. */
$app->get("/auth/dump", function ($request, $response, $arguments) {
    $data["message"] = $this->token;
    return $response->withStatus(201)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});
