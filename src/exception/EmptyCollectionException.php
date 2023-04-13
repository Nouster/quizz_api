<?php
namespace App\exception;

use Exception;

class EmptyCollectionException extends Exception{

    public function __construct(string $msg = "")
    {
        $this->message = $msg;
    }
    
}