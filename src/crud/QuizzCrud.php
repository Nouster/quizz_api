<?php

namespace App\crud;

use InvalidArgumentException;
use OutOfBoundsException;
use PDO;

class QuizzCrud
{

    public function __construct(private PDO $pdo)
    {
    }

    public function getAllQuestions(): array
    {
        $query = "SELECT * FROM quizz";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $collections = $stmt->fetchAll();
        return ($collections === false) ? [] : $collections;
    }

    public function getOneQuestion(int $id): ?array
    {
        if($id===0){
            throw new InvalidArgumentException("The specified ID is not valid.");
        }
        $query = "SELECT * FROM quizz WHERE id_quizz = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        if (!$stmt->rowCount()) {
            throw new OutOfBoundsException("The specified ID does not exist.");
        }
        $item = $stmt->fetch();
        return $item ?? null;
    }
}
