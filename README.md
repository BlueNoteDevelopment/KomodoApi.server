# KomodoApi.server

## Assumptions:
1. You have Composer installed
-- [Download and install Composer](https://getcomposer.org/download/)
2. You have Vagrant installed
-- [Download and install Vagrant](https://www.vagrantup.com/downloads.html)
3. You have VirtualBox installed
-- [Download and install VirtualBox](https://www.virtualbox.org/wiki/Downloads)

In CLI, run `composer install`

Once complete, run command `vagrant up`

Using Postman (or equivalent) make a POST call to:

Url: https://192.168.50.52/token
Authorization: Basic auth (test:test)
Headers: Content-Type: application/json
Body: RAW (JSON): ["config.all"]

Should get a response "ok".  Copy the token locally.

Now, make a GET call to:

Url: https://192.168.50.52/dump
Headers: Authorization: Bearer [PASTE TOKEN HERE]

This should output your token, something like:
```
App\Token Object
(
    [decoded] => stdClass Object
        (
            [iat] => 1481342123
            [exp] => 1481428523
            [jti] => 1EOec0bP7q6yRoshy657I
            [sub] => test
            [scope] => Array
                (
                    [0] => config.all
                )

        )
)
```