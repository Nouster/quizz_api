<?php
namespace App\exception;

use Exception;

class EmptyParameterException extends Exception {

    public function __construct(string $msg = "")
    {
        $this->message = $msg;
    }
}