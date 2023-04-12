<?php
namespace App\exception;

use App\Http\StatusCode;
use Throwable;

class ExceptionHandler
{
    public function __construct()
    {
    }
    public static function handleException()
    {
        set_exception_handler(function (Throwable $e) {
            http_response_code(StatusCode::INTERNAL_SERVER_ERROR);
            echo json_encode([
                'error' => 'An error occured.',
                'code' => $e->getCode(),
                'msg' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        });
    }
}
