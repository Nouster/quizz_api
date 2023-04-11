<?php

namespace App\crud;

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
        $query = "SELECT * FROM quizz WHERE id_quizz = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        $item = $stmt->fetch();
        return $item ?? null;
    }

}
