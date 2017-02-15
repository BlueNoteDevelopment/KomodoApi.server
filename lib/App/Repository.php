<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;

/**
 * Description of Repository
 * Returns Spot\Mapper objects fro corresponding tables
 * @author swm03
 */
class Repository {
    //put your code here
    private $locator = null;
    public function __construct($spot_locator){
        $this->locator = $spot_locator;
    }
    
    public function UserAccounts(){
        return $this->locator->mapper("App\Models\UserAccount");
    }
    
    public function EventLogs(){
        return $this->locator->mapper("App\Models\EventLog");
    }
    
    public function Configs(){
        return $this->locator->mapper("App\Models\Config");
    }
    
    public function Migrations(){
        return $this->locator->mapper("App\Models\Migration");
    }
    
    public function ServiceAccounts(){
        return $this->locator->mapper("App\Models\ServiceAccount");
    }
    
    public function Configurations(){
        return $this->locator->mapper("App\Models\Configuration");
    }
    
}
