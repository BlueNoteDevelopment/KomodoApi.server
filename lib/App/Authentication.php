<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;

/**
 * Description of Authentication
 *
 * @author swm03
 */
class Authentication {
    //put your code here
    
    static function authenticateUser($user,$password,$repository){
        
        $usermap = $repository->UserAccounts();
        $a = password_hash($password,PASSWORD_BCRYPT);
        
        $u = $usermap->all()->where(["UserAccountName =" => $user, "IsActive =" =>true , "IsLocked =" =>false ])->execute();
        
        if($u->count() === 0){
            return false;
        }else{
            //verify password
            $result  =  password_verify($password,$u[0]->EncryptedPassword);
            if($result){
                return true;
            }else{
                return false;
            }
        }
    }
    
    
}
