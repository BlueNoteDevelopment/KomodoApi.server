<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Middleware;

/**
 * Description of DBMigration
 *
 * @author swm03
 */
class DBMigration {
    //put your code here
    private $app_container = null;

    public function __construct($container) {
        $this->app_container = $container;
    }
    
    public function __invoke($request, $response, $next)
    {
        //load the ClientInfo
        $db = new \App\DBMigrationManager($this->app_container['spot'],$this->app_container['version']);
        
        $db->migrate();
        //move on
        
        return $next($request, $response);

    }
}
