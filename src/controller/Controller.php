<?php

namespace App\controller;

use App\crud\Crud;
use PDO;

abstract class Controller {
    
    protected const ACCEPTED_COLLECTION_METHODS = ["GET", "POST"];
    protected const ACCEPTED_RESOURCE_METHODS = ["GET", "PUT", "DELETE"];
    protected Crud $crud;
    protected PDO $pdo;
    protected string $uri;
    protected string $method;

    public function __construct(PDO $pdo, string $uri, string $method) {
        $this->pdo = $pdo;
        $this->uri = $uri;
        $this->method = $method;    
    }
}