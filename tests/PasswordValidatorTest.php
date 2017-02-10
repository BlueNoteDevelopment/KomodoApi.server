<?php

require_once './lib/App/PasswordValidator.php';

use PHPUnit\Framework\TestCase;

/**
 * Description of PasswordValidatorTests
 *
 * @author swm03
 */
class PasswordValidatorTests extends TestCase {
    //put your code here
    
    public function test_TestPasswordOK(){
        $candidate = 'Password12345';
        $errors = [];
        
       $result =  \App\PasswordValidator::validate($candidate,$errors);
       $this->assertEquals(true, $result);
    }
    
    public function test_TestPasswordFailBlank(){
        $candidate = '';
        $errors = [];
        
       $result =  \App\PasswordValidator::validate($candidate,$errors);
       $this->assertEquals(false, $result);
    }
    
    public function test_TestPasswordFailRequireNummber(){
       $candidate = 'aaaaaaaa';
       $errors = [];
        
       $result =  \App\PasswordValidator::validate($candidate,$errors);
       $this->assertEquals(false, $result);
    }
    
    public function test_TestPasswordPassRequireNummber(){
       $candidate = 'aaaaaaaa1';
       $errors = [];
        
       $result =  \App\PasswordValidator::validate($candidate,$errors,6,false,false,true);
       $this->assertEquals(true, $result);
    }
    
    public function test_TestPasswordPassRequireCapAndNummber(){
       $candidate = 'Aaaaaaaa1';
       $errors = [];
        
       $result =  \App\PasswordValidator::validate($candidate,$errors,6,false,true,true);
       $this->assertEquals(true, $result);
       $this->assertEquals(true, sizeof($errors)==0);
    }
    
    public function test_TestPasswordFailRequireCapAndNummber(){
       $candidate = 'aaaaaaaa1';
       $errors = [];
        
       $result =  \App\PasswordValidator::validate($candidate,$errors,6,false,true,true);
       $this->assertEquals(false, $result);
       $this->assertEquals(true, sizeof($errors)>0);
    }
    
    public function test_TestPasswordFailRequireSymbol(){
       $candidate = 'aaaaaaaa';
       $errors = [];
        
       $result =  \App\PasswordValidator::validate($candidate,$errors,6,true,false,false);
       $this->assertEquals(false, $result);
       $this->assertEquals(true, sizeof($errors)>0);
    }
   
    public function test_TestPasswordPassRequireSymbol(){
       $candidate = 'aaaaaaaa@';
       $errors = [];
        
       $result =  \App\PasswordValidator::validate($candidate,$errors,6,true,false,false);
       $this->assertEquals(true, $result);
       $this->assertEquals(true, sizeof($errors)==0);
    }
    
    public function test_TestPasswordPassComplex(){
       $candidate = 'aaaAaaaa@123!';
       $errors = [];
        
       $result =  \App\PasswordValidator::validate($candidate,$errors,6,true,true,true);
       $this->assertEquals(true, $result);
       $this->assertEquals(true, sizeof($errors)==0);
    }
    
    public function test_TestPasswordFailComplexNoCap(){
       $candidate = 'aaaaaaa@123!';
       $errors = [];
        
       $result =  \App\PasswordValidator::validate($candidate,$errors,6,true,true,true);
       $this->assertEquals(false, $result);
       $this->assertEquals(true, sizeof($errors)>0);
    }
    
    public function test_TestPasswordFailComplexNoNumber(){
       $candidate = 'aaaaaaa@A!';
       $errors = [];
        
       $result =  \App\PasswordValidator::validate($candidate,$errors,6,true,true,true);
       $this->assertEquals(false, $result);
       $this->assertEquals(true, sizeof($errors)>0);
    }
    
    public function test_TestPasswordFailComplexNoAlpha(){
       $candidate = '123456@!';
       $errors = [];
        
       $result =  \App\PasswordValidator::validate($candidate,$errors,6,true,true,true);
       $this->assertEquals(false, $result);
       $this->assertEquals(true, sizeof($errors)>0);
    }
    
    public function test_TestPasswordPassSpecialChars(){
       
       $array = array('@','!','[',']','#','$','%','^','&','*','(',')','-','+','_','`','~','<','>','.','?',',',':',';','\'','\"','|','\\','/'); 
       
        
       $errors = [];
       
       foreach($array as &$candidate){
            $result =  \App\PasswordValidator::validate($candidate,$errors,1,true,false,false);
            if(!$result){echo $candidate;}
            $this->assertEquals(true, $result);
            $this->assertEquals(true, sizeof($errors)==0);  

       }

    }
    
        public function test_TestPasswordFailSpecialChars(){
       
       $array = array('Q'); 
       
        
       $errors = [];
       
       foreach($array as &$candidate){
            $result =  \App\PasswordValidator::validate($candidate,$errors,1,true,false,false);
            if(!$result){echo $candidate;}
            $this->assertEquals(false, $result);
            $this->assertEquals(true, sizeof($errors)>0);  

       }

    }
    
}
