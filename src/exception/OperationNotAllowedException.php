<?php
namespace App\exception;

use Exception;

class OperationNotAllowedException extends Exception {

    public function __construct(string $msg = "")
    {
        $this->message = $msg;
    }
}