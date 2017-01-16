<?php

namespace App\Models;

use Spot\EntityInterface;
use Spot\MapperInterface;
use Spot\EventEmitter;

use Ramsey\Uuid;


class UserAccount extends \Spot\Entity
{
    protected static $table = "user_account";

    public static function fields()
    {
        return [
            "id" => ["type" => "integer", "unsigned" => true, "primary" => true, "autoincrement" => true],
            "user_account_name" => ["type" => "string", "length" => 255, "required"=>true, "index" => true],
            "encrypted_password" => ["type" => "string", "length" => 255, "required"=>true],
            "is_active" => ["type" => "boolean", "default" => false],
            "is_locked" => ["type" => "boolean", "default" => false],
            "failed_attempts" => ["type" => "integer", "default" => 0],
            "email_address" => ["type" => "string", "length" => 255, "default" => ''],
            "user_token_guid" => ["type" => "string", "length" => 64, "unique" => true],
            "created_datetime"   => ["type" => "datetime", "value" => new \DateTime()],
            "last_login_datetime"   => ["type" => "datetime", "value" => new \DateTime()]
        ];
    }

    public static function events(EventEmitter $emitter)
    {
        $emitter->on("beforeInsert", function (EntityInterface $entity, MapperInterface $mapper) {
            $entity->user_token_guid = Uuid::uuid4()->toString();
        });
    }

}
