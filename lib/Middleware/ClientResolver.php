<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Middleware;

use App\ClientInfo;
/**
 * ClientResolver - loads client from host name and configures database access
 *
 * @author swm03
 */
class ClientResolver {
    private $app_container = null;
    private $client = null;
    private $host = '';
    
    public function __construct($container, $hostname) {
        $this->host = $hostname;
        $this->app_container = $container;
    }
    
    public function __invoke($request, $response, $next)
    {
        //load the ClientInfo
        $ci = new \App\ClientInfo($this->host);
        //Verfiy Valid Client
        //TODO: write a module to query system database to confirm exisitance, and return user/pwd
        //
        
        $this->container['clientinfo'] = (object) array('database' => $ci->get_db_name(),
            'clientid' => $ci->get_client_id(),
            'user' => '',
            'password' => '');
        
        //move on
        
        return $next($request, $response);

    }
}
