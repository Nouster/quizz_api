<?php
require_once __DIR__ . '/../vendor/autoload.php';
use App\config\DbConfig;
use App\crud\QuizzCrud;
use Symfony\Component\Dotenv\Dotenv;



$dotenv = new Dotenv();
$dotenv->loadEnv('.env');
$pdo = DbConfig::getPdoInstance();

header('Content-type: application/json; charset=UTF-8');

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$crud = new QuizzCrud($pdo);

if($uri === "/quizz" && $method === "GET"){
   echo json_encode($crud->getAllQuestions()); 
}