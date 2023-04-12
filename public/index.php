<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\config\DbConfig;
use App\controller\QuizzController;
use App\crud\QuizzCrud;
use App\exception\ExceptionHandler;
use Symfony\Component\Dotenv\Dotenv;



$dotenv = new Dotenv();
$dotenv->loadEnv('.env');
$pdo = DbConfig::getPdoInstance();

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$uriParts = explode("/", $uri);
$uriPartsCount = count($uriParts);
$resource = $uriParts[1];


header('Content-type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: http://127.0.0.1:5500');

ExceptionHandler::handleException();

if (str_contains($uri, "/quizz")) {
    $quizzController = new QuizzController($pdo, $uri, $method, $uriParts, $uriPartsCount);
}


// $crud = new QuizzCrud($pdo);

// if ($uri === "/quizz" && $method === "GET") {
//     echo json_encode($crud->getAllQuestions());
//     exit;
// }

// if ($uriPartsCount === 3 && $uriParts[1] === "quizz" && $method === "GET") {
//     $id = intval($uriParts[2]);
//     try {
//         echo json_encode($crud->getOneQuestion($id));
//     } catch (InvalidArgumentException $e) {
//         http_response_code(400);
//         echo json_encode([
//             "error" => "An error occured",
//             "code" => 400,
//             "message" => "The specified ID is not valid."
//         ]);
//     } catch (OutOfBoundsException $e) {
//         http_response_code(404);
//         echo json_encode([
//             "error" => "An error occured",
//             "code" => 404,
//             "message" => "The specified ID does not exist."
//         ]);
//     } finally {
//         exit;
//     }
// }

// if ($uri === '/quizz' && $method === 'POST') {
//     try {
//         $data = json_decode(file_get_contents('php://input'), true);
//         $questionId = $crud->createQuestion($data);
//         http_response_code(201);
//         echo json_encode([
//             "uri" => "/quizz/" . $questionId,
//         ]);
//     } catch (InvalidArgumentException $e) {
//         http_response_code(422);
//         echo json_encode([
//             "error" => $e->getMessage(),
//             "message" => "Missing required parameters."
//         ]);
//     } catch (RuntimeException $e) {
//         http_response_code(422);
//         echo json_encode([
//             "error" => $e->getMessage(),
//             "message" => "Required parameters cannot be empty."
//         ]);
//     } finally {
//         exit;
//     }
// }

// if ($uriPartsCount === 3 && $uriParts[1] === "quizz" && $method === "DELETE") {
//     try {
//         $id = intval($uriParts[2]);
//         echo json_encode($crud->deleteQuestion($id));
//     } catch (InvalidArgumentException $e) {
//         http_response_code(400);
//         echo json_encode([
//             "error" => "An error occured",
//             "code" => 400,
//             "message" => "The specified ID is not valid."
//         ]);
//     } catch (OutOfBoundsException $e) {
//         http_response_code(404);
//         echo json_encode([
//             "error" => "An error occured",
//             "code" => 404,
//             "message" => "The specified ID does not exist."
//         ]);
//     } finally {
//         exit;
//     }
// }
