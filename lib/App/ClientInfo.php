<?php

namespace App;
use Exception\ForbiddenException;
/**
 * Class ClientInfo - Parses host referer nd displays properties for routing client request to correct database
 */
class ClientInfo{
    private  $host='';
    private  $client_id='';
    private  $db_name='';
    
    public function __construct($request_host_name) {
        $result = preg_match('/(?:http[s]*\:\/\/)*(.*?)\.(?=[^\/]*\..{2,5})/',$request_host_name,$output);
        
        if(count($output)<=1){
            throw new ForbiddenException('Invalid request. Missing Subdomain',403);
        }
        
        if(strtolower($output[1])==='www'){
            throw new ForbiddenException('Invalid request. Request cannot start with www',403);
        }
        
        $this->host = $request_host_name;
        $this->client_id = $output[1];
        $this->db_name = $this->client_id . '_komodo_db';
    }
    
    /**
     * Get the 
     * @function get_client_subdomain
     * @return string
     */
    public function get_client_id(){
        return $this->client_id;
    }
    
    public function get_db_name(){
        return $this->db_name;
    }
    
    
}