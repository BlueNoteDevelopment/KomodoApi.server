<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

class Migration extends \Spot\Entity{

    protected static $table = "migration";

    public static function fields()
    {
        return [
            "id" => ["type" => "integer", "unsigned" => true, "primary" => true, "autoincrement" => true],
            "version" => ["type" => "integer", "unsigned" => true,'default' => 0, "index" => true]    ];
    }


}






