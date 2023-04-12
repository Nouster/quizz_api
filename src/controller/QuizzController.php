<?php

namespace App\controller;

use App\crud\QuizzCrud;
use App\Http\StatusCode;
use InvalidArgumentException;
use OutOfBoundsException;
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

        $this->checkCollectionVerbs();
        $this->checkResourceVerbs();
        $this->handleCollectionGet();
        $this->handleResourceGet();
        $this->handleCreateQuestion($this->uri, $this->method);
    }

    private function checkCollectionVerbs()
    {
        if ($this->uri === "/quizz" && !in_array($this->method, self::ALLOWED_COLLECTION_VERBS)) {
            http_response_code(StatusCode::METHOD_NOT_ALLOWED);
            echo json_encode([
                'error' => 'Verbs HTTP allowed are : ' . implode(", ", self::ALLOWED_COLLECTION_VERBS)
            ]);
            exit;
        }
    }

    private function checkResourceVerbs()
    {
        if (str_contains($this->uri, "/quizz/") && !in_array($this->method, self::ALLOWED_RESOURCE_VERBS)) {
            http_response_code(StatusCode::METHOD_NOT_ALLOWED);
            echo json_encode([
                'error' => 'Verbs HTTP allowed are : ' . implode(", ", self::ALLOWED_RESOURCE_VERBS)
            ]);
            exit;
        }
    }

    private function handleCollectionGet()
    {
        if ($this->uri === "/quizz" && $this->method === "GET") {
            try {
                http_response_code(StatusCode::OK);
                echo json_encode($this->crud->getAllQuestions());
            } catch (InvalidArgumentException $e) {
                http_response_code(StatusCode::BAD_REQUEST);
                echo json_encode([
                    "error" => "An error occured",
                    "code" => 400,
                    "message" => "The specified ID is not valid."
                ]);
            } catch (OutOfBoundsException $e) {
                http_response_code(StatusCode::NOT_FOUND);
                echo json_encode([
                    "error" => "An error occured",
                    "code" => 404,
                    "message" => "The specified ID does not exist."
                ]);
            } finally {
                exit;
            }
        }
    }
    private function handleResourceGet()
    {
        if ($this->uriPartsCount === 3 && $this->uriParts[1] === "quizz" && $this->method === "GET") {
            try {
                $questionId = $this->uriParts[2];
                http_response_code(StatusCode::OK);
                echo json_encode($this->crud->getOneQuestion($questionId));
            } catch (InvalidArgumentException $e) {
                http_response_code(StatusCode::BAD_REQUEST);
                echo json_encode([
                    "error" => "An error occured",
                    "code" => 400,
                    "message" => "The specified ID is not valid."
                ]);
            } catch (OutOfBoundsException $e) {
                http_response_code(StatusCode::NOT_FOUND);
                echo json_encode([
                    "error" => "An error occured",
                    "code" => 404,
                    "message" => "The specified ID does not exist."
                ]);
            } finally {
                exit;
            }
        }
    }

    public function handleCreateQuestion($uri, $method): void 
    {
        if ($uri === "/quizz" && $method === "POST") {
            $data = json_decode(file_get_contents('php://input'), true);
            try {
                $question = $this->crud->createQuestion($data);
                http_response_code(StatusCode::CREATED);
                echo json_encode(['Your last question added' => $uri. "/".$question]);
            } catch (InvalidArgumentException $e) {
                http_response_code(StatusCode::UNPROCESSABLE_CONTENT);
                echo json_encode([
                    "error" => $e->getMessage(),
                    "message" => "Missing required parameters."
                ]);
            } catch (RuntimeException $e) {
                http_response_code(StatusCode::UNPROCESSABLE_CONTENT);
                echo json_encode([
                    "error" => $e->getMessage(),
                    "message" => "Required parameters cannot be empty."
                ]);
            } finally {
                exit;
            }
        }
    }

    public function handleUpdateQuestion(): void
    {
        if ($this->uriPartsCount === 3 && $this->uriParts[1] === "quizz" && $this->method === "PUT"){
            $idQuestion = $this->uriParts[2];
            $data = json_decode(file_get_contents("php://input"), true);
            try{
                $this->crud->updateQuestion($data, $idQuestion);
                http_response_code(StatusCode::UPDATED);
                echo json_encode(["Resource updated" => $this->uri . "/" . $idQuestion]);
            } catch (InvalidArgumentException $e){
                http_response_code(StatusCode::UNPROCESSABLE_CONTENT);
                echo json_encode([
                    "error" => $e->getMessage(),
                    "message" => "Missing required paramaters."
                ]);
            } catch (RuntimeException $e){
                http_response_code(StatusCode::UNPROCESSABLE_CONTENT);
                echo json_encode([
                    "error" => $e->getMessage(),
                    "message" => "Required parameters cannot be empty."
                ]);
            }
        }
    }
    
}
