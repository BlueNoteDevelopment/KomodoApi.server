<?php

namespace Models;

class EventLog extends \Spot\Entity{
    
    protected static $table = "eventlog";
    
    public static function fields()
    {
        return [
            "id" => ["type" => "integer", "unsigned" => true, "primary" => true, "autoincrement" => true],
            "collectionid" => ["type" => "integer", "unsigned" => true, "index" => true],
            "log_name" => ["type" => "string", "length" => 255, "value"=> 0],
            "eventdatetime_utc"   => ["type" => "datetime", "value" => new \DateTime(),"index" => "event"],
            "source" => ["type" => "string", "length" => 255],
            "ip" => ["type" => "string", "length" => 255],
            "event_code" => ["type" => "string", "length" => 25, "value"=> 0,"index" => "event"],
            "loginid" => ["type" => "integer", "unsigned" => true],
            "message" => ["type" => "string", "length" => 1048],
            "level" => ["type" => "integer", "unsigned" => true, "index" => true],
            "object_data" => ["type" => "string", "length" => 5000]        ];
    }
    
    public function timestamp()
    {
        return $this->eventdatetime_utc->getTimestamp();
    }
    
}






