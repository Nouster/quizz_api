<?php

namespace App\controller;

use App\crud\Crud;
use App\exception\OperationNotAllowedException;
use PDO;

abstract class Controller {

    protected const ACCEPTED_COLLECTION_METHODS = ["GET", "POST"];
    protected const ACCEPTED_RESOURCE_METHODS = ["GET", "PUT", "DELETE"];
    protected Crud $crud;
    protected PDO $pdo;
    protected string $uri;
    protected string $method;
    protected array $uriParts;
    protected int $uriPartsCount;

    public function __construct(PDO $pdo, string $uri, string $method, array $uriParts, int $uriPartsCount) {
        $this->pdo = $pdo;
        $this->uri = $uri;
        $this->method = $method; 
        $this->uriParts = $uriParts;
        $this->uriPartsCount = $uriPartsCount;   

        $this->checkCollectionVerbs();
        $this->checkResourceVerbs();
    }


    protected function checkCollectionVerbs(): void {
        if (!in_array($this->method, self::ACCEPTED_COLLECTION_METHODS)) {
            throw new OperationNotAllowedException("Method {$this->method} is not allowed. Method should be one of: ". implode(", ", self::ACCEPTED_COLLECTION_METHODS));
        }
    }

    protected function checkResourceVerbs(): void {
        if (!in_array($this->method, self::ACCEPTED_RESOURCE_METHODS)) {
            throw new OperationNotAllowedException("Method {$this->method} is not allowed. Method should be one of: ". implode(", ", self::ACCEPTED_RESOURCE_METHODS));
        }
    }
}