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
        $this->handleUpdateQuestion();
        $this->handleDeleteQuestion();

      
    }

    private function checkCollectionVerbs(): void
    {
        if ($this->uri === "/quizz" && !in_array($this->method, self::ALLOWED_COLLECTION_VERBS)) {
            http_response_code(StatusCode::METHOD_NOT_ALLOWED);
            echo json_encode([
                'error' => 'Verbs HTTP allowed are : ' . implode(", ", self::ALLOWED_COLLECTION_VERBS)
            ]);
            exit;
        }
    }

    private function checkResourceVerbs(): void
    {
        if (str_contains($this->uri, "/quizz/") && !in_array($this->method, self::ALLOWED_RESOURCE_VERBS)) {
            http_response_code(StatusCode::METHOD_NOT_ALLOWED);
            echo json_encode([
                'error' => 'Verbs HTTP allowed are : ' . implode(", ", self::ALLOWED_RESOURCE_VERBS)
            ]);
            exit;
        }
    }

    private function handleCollectionGet(): void
    {
        if ($this->uri === "/quizz" && $this->method === "GET") {
            try {
                http_response_code(StatusCode::OK);
                echo json_encode($this->crud->retrieveAll());
            } catch (InvalidArgumentException $e) {
                http_response_code(StatusCode::BAD_REQUEST);
                echo json_encode([
                    "error" => "An error occured",
                    "code" => 400,
                    "message" => $e->getMessage()
                ]);
            } catch (OutOfBoundsException $e) {
                http_response_code(StatusCode::NOT_FOUND);
                echo json_encode([
                    "error" => "An error occured",
                    "code" => 404,
                    "message" => $e->getMessage()
                ]);
            } finally {
                exit;
            }
        }
    }
    private function handleResourceGet(): void
    {
        if ($this->uriPartsCount === 3 && $this->uriParts[1] === "quizz" && $this->method === "GET") {
            try {
                $questionId = $this->uriParts[2];
                http_response_code(StatusCode::OK);
                echo json_encode($this->crud->retrieveOne($questionId));
            } catch (InvalidArgumentException $e) {
                http_response_code(StatusCode::BAD_REQUEST);
                echo json_encode([
                    "error" => "An error occured",
                    "code" => 400,
                    "message" => $e->getMessage()
                ]);
            } catch (OutOfBoundsException $e) {
                http_response_code(StatusCode::NOT_FOUND);
                echo json_encode([
                    "error" => "An error occured",
                    "code" => 404,
                    "message" => $e->getMessage()
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
                echo json_encode(['Your last question added' => $uri . "/" . $question]);
            } catch (InvalidArgumentException $e) {
                http_response_code(StatusCode::UNPROCESSABLE_CONTENT);
                echo json_encode([
                    "error" => $e->getMessage(),
                ]);
            } catch (RuntimeException $e) {
                http_response_code(StatusCode::UNPROCESSABLE_CONTENT);
                echo json_encode([
                    "error" => $e->getMessage(),
                ]);
            } finally {
                exit;
            }
        }
    }

    public function handleUpdateQuestion(): void
    {
        if ($this->uriPartsCount === 3 && $this->uriParts[1] === "quizz" && $this->method === "PUT") {
            $idQuestion = $this->uriParts[2];
            $data = json_decode(file_get_contents("php://input"), true);
            try {
                $this->crud->updateQuestion($data, $idQuestion);
                http_response_code(StatusCode::NOCONTENT);
                echo json_encode(["Resource updated" => $this->uri . "/" . $idQuestion]);
            } catch (InvalidArgumentException $e) {
                http_response_code(StatusCode::UNPROCESSABLE_CONTENT);
                echo json_encode([
                    "error" => $e->getMessage(),
                ]);
            } catch (RuntimeException $e) {
                http_response_code(StatusCode::UNPROCESSABLE_CONTENT);
                echo json_encode([
                    "error" => $e->getMessage(),
                ]);
            }
        }
    }

    public function handleDeleteQuestion(): void
    {
        if ($this->uriPartsCount === 3 && $this->uriParts[1] === "quizz" && $this->method === "DELETE") {
            $idQuestion = $this->uriParts[2];
            try {
                $this->crud->deleteQuestion($idQuestion);
                http_response_code(StatusCode::NOCONTENT);
                echo json_encode([
                    "message" => "Question deleted",
                    "code" => StatusCode::NOCONTENT
                ]);
            } catch (InvalidArgumentException $e) {
                http_response_code(StatusCode::UNPROCESSABLE_CONTENT);
                echo json_encode([
                    "error" => $e->getMessage(),
                ]);
            } catch (OutOfBoundsException $e) {
                http_response_code(StatusCode::UNPROCESSABLE_CONTENT);
                echo json_encode([
                    "error" => $e->getMessage()
                ]);
            }
        }
    }
}
