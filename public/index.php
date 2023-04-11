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
$id = intval($uriParts[2]);


header('Content-type: application/json; charset=UTF-8');


$crud = new QuizzCrud($pdo);

if($uri === "/quizz" && $method === "GET"){
   echo json_encode($crud->getAllQuestions()); 
}

if ($uriPartsCount === 3 && $uriParts[1] === "quizz" && $method === "GET") {
    echo json_encode($crud->getOneQuestion($id));
}