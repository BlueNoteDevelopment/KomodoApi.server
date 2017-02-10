<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;

/**
 * Description of PasswordValidator
 *
 * @author swm03
 */
class PasswordValidator {
    //put your code here
    static function validate($candidate,&$errors,$minlength=6,$requirespecial=false,$requirecap=true,$requirealphanum=true){
        $errors = [];
        
        if(strlen($candidate)<$minlength){
            $errors[] = "Password is shorter than minimum limit of " . $minlength;
        }
        
        if(!preg_match("#[0-9]+#",$candidate) && $requirealphanum){
            $errors[] = "Password must contain at least one number";
        }
        
        if(!preg_match("#[a-zA-Z]+#",$candidate) && $requirealphanum){
            $errors[] = "Password must contain at least one character";
        }
        
        if(!preg_match("/[A-Z]+/",$candidate) && $requirecap){
            $errors[] = "Password must contain at least one capital letter";
        }
        
        if(!preg_match("/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:\"\<\>,\.\?\\\]/",$candidate) && $requirespecial){
            $errors[] = "Password must contain at least one non-alphanumeric letter";
        }
        
        return count($errors)==0?true:false;
        
    }
}
