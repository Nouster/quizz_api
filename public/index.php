<?php
require_once __DIR__ . '/../vendor/autoload.php';
use App\config\DbConfig;
use App\crud\QuizzCrud;
use Symfony\Component\Dotenv\Dotenv;



$dotenv = new Dotenv();
$dotenv->loadEnv('.env');
$pdo = DbConfig::getPdoInstance();

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$uriParts = explode("/", $uri);
$uriPartsCount = count($uriParts);
var_dump($uriPartsCount);
$resource = $uriParts[1];


header('Content-type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: http://127.0.0.1:5500');

set_exception_handler(function (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Une erreur est survenue',
        'code' => $e->getCode(),
        'msg' => $e->getMessage()
    ]);
});


$crud = new QuizzCrud($pdo);

if($uri === "/quizz" && $method === "GET"){
   echo json_encode($crud->getAllQuestions());
   exit; 
}

if ($uriPartsCount === 3 && $uriParts[1] === "quizz" && $method === "GET") {
    $id = intval($uriParts[2]);
    echo json_encode($crud->getOneQuestion($id));
    exit;
}

$id = intval($uriParts[2]);
if ($id === 0) {
    http_response_code(404);
    echo json_encode([
        'error' => 'Acteur non trouvé'
    ]);
    exit;
}