<?php

namespace App;

/**
 * Description of AuthenticationResult
 *
 * @author swm03
 */
class AuthenticationResult {
    public $result = false;
    public $authName = '';
    public $guid = '';
    public $id = 0;
    
    public $authType = 'USER';  //OR SERVER
}
