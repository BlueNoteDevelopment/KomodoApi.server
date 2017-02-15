<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Spot\EntityInterface;
use Spot\MapperInterface;
use Spot\EventEmitter;

/**
 * Description of Configuration
 *
 * @author swm03
 */
class Configuration  extends \Spot\Entity {
    protected static $table = "configuration";
    //put your code here
    public static function fields()
    {
        return [
            "id" => ["type" => "integer", "unsigned" => true, "primary" => true, "autoincrement" => true],
            "service_account_id" => ["type" => "integer", "unsigned" => true, "default" => 0],
            "configuration_name" => ["type" => "string", "length" => 255, "required"=>true, "index" => true],
            "process_method" => ["type" => "string", "length" => 255, "default"=>"", "index" => true],
            "data" => ["type" => "text"],
            "is_enabled" => ["type" => "boolean", "default" => false],
            "created_datetime"   => ["type" => "datetime", "value" => new \DateTime()],
            "last_edit_datetime"   => ["type" => "datetime", "value" => new \DateTime()]
        ];
    }
    
    public static function relations(\Spot\MapperInterface $mapper, \Spot\EntityInterface $entity)
    {
        return [
            'service_account' => $mapper->belongsTo($entity, 'App\Models\ServiceAccount', 'service_account_id')
        ];
    }
    
    
}
