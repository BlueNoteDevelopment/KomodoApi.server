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
        //$a = password_hash($password,PASSWORD_BCRYPT);

        $u = $usermap->all()->where(["user_account_name =" => $user, "is_active =" =>true ])->execute();

        if($u->count() === 0){
            throw new \Exception\NotFoundException("User Does Not Exist");
            //return false;
        }else{
            if($u[0]->is_locked){
                throw new \Exception\ForbiddenException("User Account is Locked");
            }
            
            $u[0]->last_login_datetime = new \DateTime();
            //verify password
            $result  =  password_verify($password,$u[0]->encrypted_password);
            if($result){
                
                $u[0]->failed_attempts =0;
                $usermap->save($u[0]);
                               
                $auth = new AuthenticationResult();
                $auth->result = true;
                $auth->authName = $u[0]->user_account_name;
                $auth->guid = $u[0]->user_token_guid;
                $auth->id =  $u[0]->id;
                
                return $auth;
            }else{
                
                $u[0]->failed_attempts ++;
                
                if($u[0]->failed_attempts > 3){
                    $u[0]->is_locked=true;
                }
                
                $usermap->save($u[0]);
                
                throw new \Exception\ForbiddenException("Authentication Failed");
                //return false;
            }
        }
    }


}
