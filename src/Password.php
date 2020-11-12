<?php

namespace Btinet\Ringhorn;

class Password
{

    /**
     * @param $plain_password
     * @return false|string|null
     */
    public function hash($plain_password)
    {
       return password_hash($plain_password, PASSWORD_DEFAULT);
    }

    /**
     * @param $plain_password
     * @param $correct_hash
     * @return bool
     */
    public function validate($plain_password, $correct_hash)
    {
       return password_verify($plain_password, $correct_hash);
    }

}
