<?php

/*
 * (c) 2019 BENJAMIN VOIGT <benjamin.voigt@btinet.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace  Btinet\ SimpleMVC\ Modules;

use Btinet\ SimpleMVC\ Core;

class User extends Core\Model {


	public function doLogin($data) {         
        $account = $this->checkLoginData($data);
        if ( $account ) {
            $_SESSION['account']['login'] = true; 

            foreach ($account as $key => $value){
                $_SESSION['account']["$key"] = $value;
            }
            echo 'ok!';  
            print_r($_SESSION);                
            return true;
        }	else {
            echo 'nicht ok!';
            print_r($_SESSION);
            session_destroy();              
            return false;
        }				
    }
    
    private function checkLoginData($data) {                
        return $this->_db->select(' SELECT * FROM accounts WHERE accountName = :accountName AND accountPass = :accountPass LIMIT 1 ', $data, true);					
	}

}