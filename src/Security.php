<?php


namespace Btinet\Ringhorn;

use Btinet\Ringhorn\Controller\AbstractController;

class Security
{

    protected $user = null;

    protected Session $session;

    function __construct(Session $session){
        $this->session = $session;
    }

    public function isGranted(string $role){
        if ($this->session->get('user')) {
            if(in_array($role,$this->session->get('user','roles'))){
                return true;
            }
        }
        return false;
    }

    public function denyAccessUntilGranted(string $role){
        if ($this->session->get('login')) {
            if(in_array($role,$this->session->get('user','roles'))){
                return false;
            }
        }
        return AbstractController::halt();
    }

}
