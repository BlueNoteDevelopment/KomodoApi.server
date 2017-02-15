<?php

$app->post("/api/useraccount", function ($request, $response, $arguments) {
    $data = $request->getParsedBody();


    if ($data === null || $data === '' ){
        throw new Exception("POST data is empty", 500);
    }
    $post = array_change_key_case($data);
    unset($data);
    //$token = $this->token;
    
    if (!isset($post['username'])){
        throw new Exception\PreconditionFailedException('Missing User Name Parameter',500);
    }
    
    if (!isset($post['password'])){
        throw new Exception\PreconditionFailedException('Invalid Password Parameter',500);
    }
    


    $mapper = $this->repository->UserAccounts();
    $user = $mapper->first(['user_account_name' =>  $post['username']]);
    
    $code = 201;
    
    if($user){
        $result = ["status" => "EEXIST", "code" =>1, "user_guid" => $user->user_token_guid ];
        $code = 200;
    }else{
        //check password
        $errors = [];
        if(!\App\PasswordValidator::validate($post['password'],$errors,6,true,true,true)){
            $result = ["status" => "BADPASSWORD", "code" =>2, "errors" => $errors ];
            $code = 400;
        }else{
            $user = new App\Models\UserAccount();
            $user->user_account_name = $post['username'];
            $user->encrypted_password = password_hash($post['password'], PASSWORD_DEFAULT);
            $user->is_active = (isset($post['isactive']))? $post['isactive'] : true;
            $user->email_address = (isset($post['email']))? $post['email'] : '';

            $mapper->save($user);
            $result = ["status" => "OK", "code" =>0, "user_guid" => $user->user_token_guid ];  
        }
    }

    return $response->withStatus($code)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));


});

$app->put("/api/useraccount/{id:[0-9]+}", function ($request, $response, $arguments) {
    $data = $request->getParsedBody();


    if ($data === null || $data === '' ){
        throw new Exception("POST data is empty", 500);
    }
    $post = array_change_key_case($data);
    unset($data);
    //$token = $this->token;
    
    if (!isset($post['username'])){
        throw new Exception\PreconditionFailedException('Missing User Name Parameter',500);
    }
    

    


    $mapper = $this->repository->UserAccounts();
    $user = $mapper->first(['id' =>  $arguments['id']]);
    
    $code = 200;
    
    if(!$user){
        $result = ["status" => "NOEXIST", "code" =>1];
        $code = 404;
    }else{
        //check password

        if(strtolower($user->user_account_name) != strtolower($post['username'])){
            //changed name
            $check = $mapper->first(['user_account_name' =>  $post['username'], 'id !=' =>  $arguments['id'] ]);
            if($check){
                $result = ["status" => "USERNAMEEXISTS", "code" =>2 ];
                $code = 400;
            }else{
                $user->user_account_name = $post['username'];
            }
            unset($check);
        }

        if (isset($post['newpassword'])){
            $errors = [];
            if(!\App\PasswordValidator::validate($post['newpassword'],$errors,6,true,true,true)){
                $result = ["status" => "BADPASSWORD", "code" =>2, "errors" => $errors ];
                $code = 400;
            }else{
                $user->encrypted_password = password_hash($post['newpassword'], PASSWORD_DEFAULT);
            }
        }
        
        
        $user->is_active = (isset($post['isactive']))? $post['isactive'] : true;
        
        if(isset($post['email'])){
            $user->email_address =  $post['email'];
        }
        
        
        if($code ==200){
            $mapper->save($user);
            $result = ["status" => "OK", "code" =>0, "user_guid" => $user->user_token_guid ];  
        }

    }

    return $response->withStatus($code)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));


});




$app->get("/api/useraccount/", function ($request, $response, $arguments) {

    $mapper = $this->repository->UserAccounts();  
    $entities = $mapper->all()->order(["user_account_name" => "ASC"])->execute();
    $result = $entities->jsonSerialize();
    
    if($result){
        foreach($result as &$e ){
            unset($e["encrypted_password"]);
        }
    }
    
    return $response->withStatus(200)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});

$app->get("/api/useraccount/{id:[0-9]+}", function ($request, $response, $arguments) {


    $mapper = $this->repository->UserAccounts();  
    
    $entities = $mapper->get($arguments['id']);
    
    if(!$entities){
       // $result = (object)[];
        
        throw new Exception('Entity Not Found',404);
    }else{
        
        $result = $entities->jsonSerialize();
        unset($result["encrypted_password"]);
    }

    return $response->withStatus(200)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});

$app->delete("/api/useraccount/{id:[0-9]+}", function ($request, $response, $arguments) {
    
    $mapper = $this->repository->UserAccounts(); 
    $user = $mapper->first(['id' => $arguments['id']]);
    
    if($user){
        $mapper->delete(["id"=>$arguments['id']]);
        
        //may want to check to see if user is admin first
        
        $result = ["status" => "OK", "code" =>0];
    }else{
        throw new Exception('Entity Not Found',404); 
    }
    
    return $response->withStatus(200)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});