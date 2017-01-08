<?php

namespace App\Models;

class EventLog extends \Spot\Entity{
    
    protected static $table = "EventLog";
    
    public static function fields()
    {
        return [
            "Id" => ["type" => "integer", "unsigned" => true, "primary" => true, "autoincrement" => true],
            "CollectionId" => ["type" => "integer", "unsigned" => true, "index" => true],
            "LogName" => ["type" => "string", "length" => 255, "value"=> 0],
            "EventDatetimeUtc"   => ["type" => "datetime", "value" => new \DateTime(),"index" => "event"],
            "Source" => ["type" => "string", "length" => 255],
            "Ip" => ["type" => "string", "length" => 255],
            "EventCode" => ["type" => "string", "length" => 25, "value"=> 0,"index" => "event"],
            "LoginId" => ["type" => "integer", "unsigned" => true],
            "Message" => ["type" => "string", "length" => 1048],
            "Level" => ["type" => "integer", "unsigned" => true, "index" => true],
            "ObjectData" => ["type" => "string", "length" => 5000]        ];
    }
    
    public function timestamp()
    {
        return $this->eventdatetime_utc->getTimestamp();
    }
    
}






