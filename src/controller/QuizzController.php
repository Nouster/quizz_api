<?php

namespace App\controller;

use App\crud\Crud;
use App\crud\QuizzCrud;
use App\exception\EmptyCollectionException;
use App\exception\EmptyParameterException;
use App\exception\IdNotFoundException;
use App\Http\StatusCode;
use InvalidArgumentException;
use PDO;

class QuizzController extends Controller
{
    public PDO $pdo;
    protected string $uri;
    protected string $method;
    protected array $uriParts;
    protected int $uriPartsCount;
    protected Crud $crud;
    public const ALLOWED_COLLECTION_VERBS = ["GET", "POST"];
    public const ALLOWED_RESOURCE_VERBS = ["GET", "PUT", "DELETE"];






    public function generalHandle(): void
    {
        $this->crud = new QuizzCrud($this->pdo);
        $this->handleCollectionGet();
        $this->handleResourceGet();
        $this->handleCreateQuestion($this->uri, $this->method);
        $this->handleUpdateQuestion();
        $this->handleDeleteQuestion();
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
            } catch (EmptyCollectionException $e) {
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
            } catch (IdNotFoundException $e) {
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
                $question = $this->crud->createItem($data);
                http_response_code(StatusCode::CREATED);
                echo json_encode(['Your last question added' => $uri . "/" . $question]);
            } catch (InvalidArgumentException $e) {
                http_response_code(StatusCode::UNPROCESSABLE_CONTENT);
                echo json_encode([
                    "error" => $e->getMessage(),
                ]);
            } catch (EmptyParameterException $e) {
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
                $this->crud->updateItem($data, $idQuestion);
                http_response_code(StatusCode::NOCONTENT);
                echo json_encode(["Resource updated" => $this->uri . "/" . $idQuestion]);
            } catch (InvalidArgumentException $e) {
                http_response_code(StatusCode::UNPROCESSABLE_CONTENT);
                echo json_encode([
                    "error" => $e->getMessage(),
                ]);
            } catch (EmptyParameterException $e) {
                http_response_code(StatusCode::UNPROCESSABLE_CONTENT);
                echo json_encode([
                    "error" => $e->getMessage(),
                ]);
            } finally {
                exit;
            }
        }
    }

    public function handleDeleteQuestion(): void
    {
        if ($this->uriPartsCount === 3 && $this->uriParts[1] === "quizz" && $this->method === "DELETE") {
            $idQuestion = $this->uriParts[2];
            try {
                $this->crud->deleteItem($idQuestion);
                http_response_code(StatusCode::NOCONTENT);
                echo json_encode([
                    "message" => "Item deleted",
                    "code" => StatusCode::NOCONTENT
                ]);
            } catch (InvalidArgumentException $e) {
                http_response_code(StatusCode::UNPROCESSABLE_CONTENT);
                echo json_encode([
                    "error" => $e->getMessage(),
                ]);
            } catch (IdNotFoundException $e) {
                http_response_code(StatusCode::UNPROCESSABLE_CONTENT);
                echo json_encode([
                    "error" => $e->getMessage()
                ]);
            } finally {
                exit;
            }
        }
    }
}
