<?php

namespace App\controller;

use App\crud\QuizzCrud;
use InvalidArgumentException;
use PDO;
use RuntimeException;

class QuizzController
{
    private PDO $pdo;
    private string $uri;
    private string $method;
    private array $uriParts;
    private int $uriPartsCount;
    private QuizzCrud $crud;
    public const ALLOWED_COLLECTION_VERBS = ["GET", "POST"];
    public const ALLOWED_RESOURCE_VERBS = ["GET", "PUT", "DELETE"];

    public function __construct(PDO $pdo, string $uri, string $method, array $uriParts, int $uriPartsCount)
    {
        $this->pdo = $pdo;
        $this->uri = $uri;
        $this->method = $method;
        $this->uriParts = $uriParts;
        $this->uriPartsCount = $uriPartsCount;
        $this->crud = new QuizzCrud($pdo);

        if ($uri === "/quizz" && !in_array($this->method, self::ALLOWED_COLLECTION_VERBS)) {
            http_response_code(405);
            echo json_encode([
                'error' => 'Verbs HTTP allowed are : ' . implode(", ", self::ALLOWED_COLLECTION_VERBS)
            ]);
            exit;
        }

        if (str_contains($uri, "/quizz/") && !in_array($this->method, self::ALLOWED_RESOURCE_VERBS)) {
            http_response_code(405);
            echo json_encode([
                'error' => 'Verbs HTTP allowed are : ' . implode(", ", self::ALLOWED_RESOURCE_VERBS)
            ]);
            exit;
        }

        if ($uri === "/quizz" && $method === "GET") {
            http_response_code(200);
            echo json_encode($this->crud->getAllQuestions());
            exit;
        }
    }
}
