<?php

namespace App\controller;

use App\crud\QuizzCrud;
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
            try {
                http_response_code(200);
                echo json_encode($this->crud->getAllQuestions());
            } catch (InvalidArgumentException $e) {
                http_response_code(400);
                echo json_encode([
                    "error" => "An error occured",
                    "code" => 400,
                    "message" => "The specified ID is not valid."
                ]);
            } catch (OutOfBoundsException $e) {
                http_response_code(404);
                echo json_encode([
                    "error" => "An error occured",
                    "code" => 404,
                    "message" => "The specified ID does not exist."
                ]);
            } finally {
                exit;
            }
        }

        if ($this->uriPartsCount === 3 && $this->uriParts[1] === "quizz" && $this->method === "GET") {
            try {
                $questionId = $this->uriParts[2];
                http_response_code(200);
                echo json_encode($this->crud->getOneQuestion($questionId));
            } catch (InvalidArgumentException $e) {
                http_response_code(400);
                echo json_encode([
                    "error" => "An error occured",
                    "code" => 400,
                    "message" => "The specified ID is not valid."
                ]);
            } catch (OutOfBoundsException $e) {
                http_response_code(404);
                echo json_encode([
                    "error" => "An error occured",
                    "code" => 404,
                    "message" => "The specified ID does not exist."
                ]);
            } finally {
                exit;
            }
        }
        if ($uri === "/quizz" && $method === "POST") {
            $data = json_decode(file_get_contents('php://input'), true);
            try {
                json_encode($this->crud->createQuestion($data));
            } catch (InvalidArgumentException $e) {
                http_response_code(422);
                echo json_encode([
                    "error" => $e->getMessage(),
                    "message" => "Missing required parameters."
                ]);
            } catch (RuntimeException $e) {
                http_response_code(422);
                echo json_encode([
                    "error" => $e->getMessage(),
                    "message" => "Required parameters cannot be empty."
                ]);
            } finally {
                exit;
            }
        }
    }
}
