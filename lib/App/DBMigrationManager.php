<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;

/**
 * Description of DBMigrationManager
 *
 * @author swm03
 */
class DBMigrationManager {
    //put your code here
    private $locator=null;
    private $version = 0;
    
    public function __construct($spot_locator, $version) {
        $this->locator = $spot_locator;
        $this->version = $version;
    }
    
    public function migrate(){
        
        if($this->locator === null){
            throw new Exception('Db Context not supplied');
        }
        
        $v = $this->locator->mapper('App\Models\Migration')->all()->where(['Version >=' => $this->version]);
         
         
        if ($v->count()===0){
            //ime to migrate
            $this->locator->mapper("App\Models\Config")->migrate();
            $this->locator->mapper("App\Models\EventLog")->migrate();
            $this->locator->mapper("App\Models\UserAccount")->migrate();
            $mapper = $this->locator->mapper("App\Models\Migration");
            
            
            $migrate = $mapper->build(['Version' => $this->version]);
            $result =  $mapper->save($migrate);
            
            
        }
        
        return true;
        
    }
    
   
}
