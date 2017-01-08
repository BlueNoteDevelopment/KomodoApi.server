<?php

namespace App\Models;

use Spot\EntityInterface;
use Spot\MapperInterface;
use Spot\EventEmitter;

use Ramsey\Uuid;


class UserAccount extends \Spot\Entity
{
    protected static $table = "UserAccount";

    public static function fields()
    {
        return [
            "Id" => ["type" => "integer", "unsigned" => true, "primary" => true, "autoincrement" => true],
            "UserAccountName" => ["type" => "string", "length" => 255, "required"=>true, "index" => true],
            "EncryptedPassword" => ["type" => "string", "length" => 255, "required"=>true],
            "IsActive" => ["type" => "boolean", "default" => false],
            "IsLocked" => ["type" => "boolean", "default" => false],
            "FailedAttempts" => ["type" => "integer", "default" => 0],
            "EmalAddress" => ["type" => "string", "length" => 255, "default" => ''],
            "UserTokenGuid" => ["type" => "string", "length" => 64, "unique" => true],
            "CreatedDateTime"   => ["type" => "datetime", "value" => new \DateTime()],
            "LastLoginDateTime"   => ["type" => "datetime", "value" => new \DateTime()]
        ];
    }

    public static function events(EventEmitter $emitter)
    {
        $emitter->on("beforeInsert", function (EntityInterface $entity, MapperInterface $mapper) {
            $entity->UserTokenGuid = Uuid::uuid4()->toString();
        });
    }

}
