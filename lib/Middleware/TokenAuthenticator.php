<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Middleware;

/**
 * Description of TokenAuthenticator
 *
 * @author swm03
 */
class TokenAuthenticator {
    //put your code here
    
    private $app_container = null;

    public function __construct($container) {
        $this->app_container = $container;
    }
    
    public function __invoke($request, $response, $next)
    {
        if ((!$this->app_container["token"]->decoded)===null){
            if($this->app_container["token"]->decoded->sub->authType ==='USER'){
                \App\Authentication::verifyUserFromGuid($this->app_container["token"]->decoded->sub->guid, $this->app_container['repository']);
            }else{
                \App\Authentication::verifyServiceFromGuid($this->app_container["token"]->decoded->sub->guid, $this->app_container['repository']);
            }
        }
        return $next($request, $response);

    }
                

    
    
}
