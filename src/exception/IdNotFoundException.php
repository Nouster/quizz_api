<?php
namespace App\exception;

use Exception;

class IdNotFoundException extends Exception{

    public function __construct(string $msg = "")
    {
        $this->message = $msg;
    }

}