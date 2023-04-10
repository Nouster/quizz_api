<?php

namespace App\crud;
use PDO;

class QuizzCrud {

    public function __construct(private PDO $pdo )
    {
        
    }

    public function getAllQuestions(): array {
        $query = "SELECT * FROM quizz";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $collections = $stmt->fetchAll();
        return ($collections === false) ? [] : $collections;
}

public function getOneQuestion($id): ?array
{
    $query = "SELECT * FROM products WHERE id = :id";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute(['id' => $id]);
    $product = $stmt->fetch(\PDO::FETCH_ASSOC);

    return ($product === false) ? null : $product;
}

}