<?php

namespace App\Http;

class StatusCode
{
    const OK = 200;
    const CREATED = 201;
    const NOCONTENT = 204;
    const BAD_REQUEST = 400;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const UNPROCESSABLE_CONTENT = 422;
    const INTERNAL_SERVER_ERROR = 500;
}