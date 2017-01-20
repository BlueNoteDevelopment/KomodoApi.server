<?php

$app->post("/api/serviceaccount", function ($request, $response, $arguments) {
    $data = $request->getParsedBody();
    
    if ($data===null || $data==='' ){
        throw new Exception("POST data is empty",500);
    }
    $post = array_change_key_case($data);
    unset($data);
    $token = $this->token;
    //Do stuff here
    
    if (!isset($post['hostname'])){
        throw new Exception\PreconditionFailedException('Missing Hostname Parameter',500);
    }
        
    //$svc = new App\Models\ServiceAccount();
    $mapper = $this->repository->ServiceAccounts();
    $svc = $mapper->first(['service_host_name' =>  $post['hostname']]);
    
    if($svc){
        $result = ["status" => "EEXIST", "code" =>1, "service_guid" => $svc->service_token_guid ];
    }else{
    //POST make hostname unique
        $svc = new App\Models\ServiceAccount();
        $svc->service_host_name = $post['hostname'];
        $svc->is_active = (isset($post['isactive']))? $post['isactive'] : true;
        $mapper->save($svc);
        $result = ["status" => "OK", "code" =>0, "service_guid" => $svc->service_token_guid ];
    }

    return $response->withStatus(201)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    
});

$app->put("/api/serviceaccount/{id:[0-9]+}", function ($request, $response, $arguments) {
    $data = $request->getParsedBody();
    
    if ($data===null || $data==='' ){
        throw new Exception("POST data is empty",500);
    }
    $post = array_change_key_case($data);
    unset($data);
    $token = $this->token;
    //Do stuff here
    
    if (!isset($post['hostname'])){
        throw new Exception\PreconditionFailedException('Missing Hostname Parameter',500);
    }
        
    $mapper = $this->repository->ServiceAccounts(); 
    $svc = $mapper->first(['id' => $arguments['id']]);
    //POST make hostname unique
    if($svc){
        try{
            $svc->id = $arguments['id'];
            $svc->service_host_name = $post['hostname'];
            $svc->is_active = (isset($post['isactive']))? $post['isactive'] : true;
            $mapper->update($svc);

        } catch (Exception $ex) {
            throw new Exception('Duplicate Hostname Found',401);  
        }   
    }else{
        throw new Exception('Entity Not Found',404);  
    }

    
    $result = ["status" => "OK", "code" =>0];

    return $response->withStatus(201)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    
});

$app->put("/api/serviceaccount/{hostname}", function ($request, $response, $arguments) {
    $data = $request->getParsedBody();
    
    if ($data===null || $data==='' ){
        throw new Exception("POST data is empty",500);
    }
    $post = array_change_key_case($data);
    unset($data);
    $token = $this->token;
    //Do stuff here
    
    if ($arguments['hostname'] === ''){
        throw new Exception\PreconditionFailedException('Missing Hostname Parameter',500);
    }
        
    //$svc = new App\Models\ServiceAccount();
    $mapper = $this->repository->ServiceAccounts(); 
    $svc = $mapper->first(['service_host_name' => $arguments['hostname']]);
    
    //POST make hostname unique
    if($svc){
        $svc->service_host_name = $arguments['hostname'];
        $svc->is_active = (isset($post['isactive']))? $post['isactive'] : true;
        $mapper->update($svc);
    }else{
        throw new Exception('Entity Not Found',404);  
    }

    
    $result = ["status" => "OK", "code" =>0];

    return $response->withStatus(201)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    
});

$app->get("/api/serviceaccount/{id:[0-9]+}", function ($request, $response, $arguments) {
    $mapper = $this->repository->ServiceAccounts();  
    
    $entities = $mapper->get($arguments['id']);
    
    if(!$entities){
       // $result = (object)[];
        throw new Exception('Entity Not Found',404);
    }else{
        $result = $entities->jsonSerialize();
    }
    
    

    return $response->withStatus(200)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});

$app->get("/api/serviceaccount/{hostname}", function ($request, $response, $arguments) {
    
    $mapper = $this->repository->ServiceAccounts();  
    $entities = $mapper->all()->where(["service_host_name =" => $arguments['hostname'] ])->execute();

     if(!$entities){
       // $result = (object)[];
        throw new Exception('Entity Not Found',404);
    }else{
        $result = $entities->jsonSerialize();
    }
    
    
    return $response->withStatus(200)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});

$app->get("/api/serviceaccount", function ($request, $response, $arguments) {
    
    $mapper = $this->repository->ServiceAccounts();  
    $entities = $mapper->all()->order(["service_host_name" => "ASC"])->execute();
    $result = $entities->jsonSerialize();

    return $response->withStatus(200)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});

$app->delete("/api/serviceaccount/{id:[0-9]+}", function ($request, $response, $arguments) {
    
    
    $mapper = $this->repository->ServiceAccounts(); 
    $mapper->delete(["id"=>$arguments['id']]);
    $result = ["status" => "OK", "code" =>0];

    return $response->withStatus(201)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});

$app->delete("/api/serviceaccount/{hostname}", function ($request, $response, $arguments) {
    
    $mapper = $this->repository->ServiceAccounts(); 
    $mapper->delete(["service_host_name =" => $arguments['hostname']]);
    
    $result = ["status" => "OK", "code" =>0];

    return $response->withStatus(201)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});