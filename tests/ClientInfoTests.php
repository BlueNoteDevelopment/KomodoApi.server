<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once './lib/Exception/ForbiddenException.php';
require_once './lib/App/ClientInfo.php';

use PHPUnit\Framework\TestCase;

/**
 * Description of ClientInfoTests
 *
 * @author swm03
 */
class ClientInfoTests extends TestCase {
    //put your code here
    /** @test */
    public function test_LoadObjectWithValidHostName(){
        $host = 'customer1.komodo.local';
        
        $ci = new App\ClientInfo($host);
        
        $this->assertEquals('customer1', $ci->get_client_id());
        $this->assertEquals('customer1_komodo_db', $ci->get_db_name());
    }
    
    public function test_ThrowsExceptionInvalidHost(){
        $host = 'komodo.local';
        $this->expectException(Exception\ForbiddenException::class);
        
        $ci = new App\ClientInfo($host);
    }
    
    public function test_ThrowsExceptionHostStartsWithWWW(){
        $host = 'komodo.local';
        $this->expectException(Exception\ForbiddenException::class);
        
        $ci = new App\ClientInfo($host);
    }
    
}
