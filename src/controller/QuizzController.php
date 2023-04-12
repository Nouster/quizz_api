<?php

namespace App\controller;

use App\crud\QuizzCrud;
use InvalidArgumentException;
use PDO;

class QuizzController
{
    private PDO $pdo;
    private string $uri;
    private string $httpMethod;
    private array $uriParts;
    private int $uriPartsCount;
    private QuizzCrud $crud;
    public const ALLOWED_COLLECTION_VERBS = ["GET", "POST"];
    public const ALLOWED_RESOURCE_VERBS = ["GET", "PUT", "DELETE"];

    public function __construct(PDO $pdo, string $uri, string $httpMethod, array $uriParts, int $uriPartsCount)
    {
        $this->pdo = $pdo;
        $this->uri = $uri;
        $this->httpMethod = $httpMethod;
        $this->uriParts = $uriParts;
        $this->uriPartsCount = $uriPartsCount;
        $this->crud = new QuizzCrud($pdo);

        if ($uri === "/actor" && !in_array($this->httpMethod, self::ALLOWED_COLLECTION_VERBS)) {
            http_response_code(405);
            echo json_encode([
                'error' => 'Verbs HTTP allowed are : ' . implode(", ", self::ALLOWED_COLLECTION_VERBS)
            ]);
            exit;
        }

        if (str_contains($uri, "/actor/") && !in_array($this->httpMethod, self::ALLOWED_RESOURCE_VERBS)) {
            http_response_code(405);
            echo json_encode([
                'error' => 'Verbs HTTP allowed are : ' . implode(", ", self::ALLOWED_RESOURCE_VERBS)
            ]);
            exit;
        }

        if($uri === "/quizz" && $httpMethod === "GET"){
            http_response_code(200);
            echo json_encode($this->crud->getAllQuestions());
            exit;
        }


        if($uri === "/quizz" && $httpMethod === "POST"){
            try {
            http_response_code(201);
            echo json_encode($this->crud->getAllQuestions());
            exit;
            }catch(InvalidArgumentException $e){
                http_response_code(422);
                echo json_encode([
                    "error" => $e->getMessage(),
                    "message" => "Missing required parameters."]);
            }
        }
    }
}
