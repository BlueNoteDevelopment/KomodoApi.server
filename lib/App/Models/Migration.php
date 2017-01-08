<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

class Migration extends \Spot\Entity{
    
    protected static $table = "Migrations";
    
    public static function fields()
    {
        return [
            "Id" => ["type" => "integer", "unsigned" => true, "primary" => true, "autoincrement" => true],
            "Version" => ["type" => "integer", "unsigned" => true,'default' => 0, "index" => true]    ];
    }
    
    
}






