<?php


$app->post("/api/configuration", function ($request, $response, $arguments) {
    $data = $request->getParsedBody();


    if ($data === null || $data === '' ){
        throw new Exception("POST data is empty", 500);
    }
    $post = array_change_key_case($data);
    unset($data);
    $token = $this->token;
    //Do stuff here
    if (!isset($post['configurationname'])){
        throw new Exception\PreconditionFailedException('Missing Configuration Name Parameter',500);
    }

    $mapper = $this->repository->Configurations();
    $config = $mapper->first(['configuration_name' =>  $post['configurationname']]);
    
    $code = 201;
    
    if($config){
        $result = ["status" => "EEXIST", "code" =>1, "id" => $config->id ];
        $code = 200;
    }else{
       
        $config = new App\Models\Configuration();
        $config->service_account_id = (isset($post['serviceaccountid']))? $post['serviceaccountid'] : 0;
        $config->configuration_name = $post['configurationname'];
        $config->process_method = $post['processmethod'];
        $config->is_enabled = (isset($post['isenabled']))? $post['isenabled'] : true;
        $config->last_edit_datetime = new \DateTime();
        
        if(is_array($post['data'])){
            $config->data = json_encode($post['data']);
        }else{
            $config->data = $post['data'];
        }
        
        
        $mapper->save($config);
        $result = ["status" => "OK", "code" =>0, "id" => $config->id ];  
        
    }

    return $response->withStatus($code)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));


});

$app->put("/api/configuration/{id:[0-9]+}", function ($request, $response, $arguments) {
    $data = $request->getParsedBody();


    if ($data === null || $data === '' ){
        throw new Exception("POST data is empty", 500);
    }
    $post = array_change_key_case($data);
    unset($data);
    //Do stuff here

    $mapper = $this->repository->Configurations();
    $config = $mapper->first(['id' =>  $arguments['id']]);
    
    $code = 200;
    
    if(!$config){
        $result = ["status" => "NOEXIST", "code" =>1];
        $code = 404;
    }else{
        
        if(strtolower($config->configuration_name) != strtolower($post['configurationname'])){
            //changed name
            $check = $mapper->first(['configuration_name' =>  $post['configurationname'], 'id !=' =>  $arguments['id'] ]);
            if($check){
                $result = ["status" => "CONFIGNAMEEXISTS", "code" =>2 ];
                $code = 400;
            }else{
                $config->configuration_name = $post['configurationname'];
            }
            unset($check);
        }
        
        if(isset($post['processmethod'])){$config->process_method =  $post['processmethod']; }
        if(isset($post['data'])){
            if(is_array($post['data'])){
                $config->data = json_encode($post['data']);
            }else{
                $config->data = $post['data'];
            }
            
        }
        if(isset($post['serviceaccountid'])){$config->service_account_id =  $post['serviceaccountid']; }
        if(isset($post['isenabled'])){$config->is_enabled =  $post['isenabled']; }
        
        
        
        if($code ==200){
            $mapper->save($config);
            $result = ["status" => "OK", "code" =>0, "id" => $config->id ];  
        }

    }

    return $response->withStatus($code)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));


});

$app->get("/api/configuration/{id:[0-9]+}", function ($request, $response, $arguments) {

    $mapper = $this->repository->Configurations();  
    $entities = $mapper->first(["id" => $arguments['id']]);

    $result = $entities->jsonSerialize();

    return $response->withStatus(200)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});

$app->get("/api/configuration[/{serviceaccount}[/{active}]]", function ($request, $response, $arguments) {


    $mapper = $this->repository->Configurations();  
    
    if(isset($arguments['serviceaccount'])){
        
        $svcmapper = $this->repository->ServiceAccounts(); 
        
        if(isset($arguments['active'])){
            $svc = $svcmapper->first(["service_host_name" =>$arguments['serviceaccount'],"is_active"=>true ]);
        }else{
            $svc = $svcmapper->first(["service_host_name" =>$arguments['serviceaccount'] ]);
        }

        if($svc){
            $svcid = $svc->id;
        }else{
            $svcid = -1;
        }
        
        $entities = $mapper->where(["service_account_id" => $svcid])->order(["configuration_name" => "ASC"])->execute();
    }else{
        $entities = $mapper->all()->order(["configuration_name" => "ASC"])->execute();
    }
    
    $result = $entities->jsonSerialize();


    return $response->withStatus(200)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});



$app->delete("/api/configuration/{id:[0-9]+}", function ($request, $response, $arguments) {

    $mapper = $this->repository->Configurations(); 
    $config = $mapper->first(['id' => $arguments['id']]);
    
    if($config){
        $mapper->delete(["id"=>$arguments['id']]);
        $result = ["status" => "OK", "code" =>0];
    }else{
        throw new Exception('Entity Not Found',404); 
    }

    return $response->withStatus(200)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});