<?php

namespace App\Models;

use Spot\EntityInterface;
use Spot\MapperInterface;
use Spot\EventEmitter;

use Ramsey\Uuid;


class ServiceAccount extends \Spot\Entity
{
    protected static $table = "service_account";

    public static function fields()
    {
        return [
            "id" => ["type" => "integer", "unsigned" => true, "primary" => true, "autoincrement" => true],
            "service_host_name" => ["type" => "string", "length" => 255, "required"=>true, "index" => true],
            "is_active" => ["type" => "boolean", "default" => false],
            "service_token_guid" => ["type" => "string", "length" => 64, "unique" => true],
            "created_datetime"   => ["type" => "datetime", "value" => new \DateTime()],
            "last_access_datetime"   => ["type" => "datetime", "value" => new \DateTime()]
        ];
    }

    public static function events(EventEmitter $emitter)
    {
        $emitter->on("beforeInsert", function (EntityInterface $entity, MapperInterface $mapper) {
            $entity->service_token_guid = Uuid::uuid4()->toString();
        });
    }

}
