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
        if ($id === 0) {
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

   

    public function createQuestion(array $data): int
    {
        if (!isset($data['question_quizz'], $data['lvl_quizz'], $data['type_quizz'])) {
            throw new InvalidArgumentException('Missing required parameters.');
        }
        $query = "INSERT INTO quizz VALUES (null, :question_quizz, :lvl_quizz, :type_quizz)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            "question_quizz" => $data['question_quizz'],
            "lvl_quizz" => $data['lvl_quizz'],
            "type_quizz" => $data['type_quizz']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function deleteQuestion($id): bool
    {
        if ($id === 0) {
            throw new InvalidArgumentException("The specified ID is not valid.");
        }
        $query = "DELETE FROM quizz WHERE id_quizz = :id_quizz";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(["id_quizz" => $id]);
        if (!$stmt->rowCount()) {
            throw new OutOfBoundsException("The specified ID does not exist.");
        }
        return ($stmt->rowCount() > 0);
    }
}
