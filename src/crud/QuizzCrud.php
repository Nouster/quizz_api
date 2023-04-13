<?php

namespace App\crud;

use InvalidArgumentException;
use OutOfBoundsException;
use RuntimeException;

class QuizzCrud extends Crud
{
    protected string $table = 'quizz';
    protected array $column = ["id_quizz", "question_quizz", "lvl_quizz", "type_quizz"];

   

    public function retrieveAll(): array
    {
        return parent::retrieveAll();
    }

    // public function getAllQuestions(): array
    // {
    //     $query = "SELECT * FROM quizz";
    //     $stmt = $this->pdo->prepare($query);
    //     $stmt->execute();
    //     $collections = $stmt->fetchAll();
    //     return $collections ?: [];
    // }

        public function retrieveOne(int $id): ?array
        {
            return parent::retrieveOne($id);
        }


    // public function getOneQuestion(int $id): ?array
    // {
    //     if ($id === 0) {
    //         throw new InvalidArgumentException("The specified ID is not valid.");
    //     }
    //     $query = "SELECT * FROM quizz WHERE id_quizz = :id";
    //     $stmt = $this->pdo->prepare($query);
    //     $stmt->execute(['id' => $id]);
    //     if (!$stmt->rowCount()) {
    //         throw new OutOfBoundsException("The specified ID does not exist.");
    //     }
    //     $item = $stmt->fetch();
    //     return $item ?? null;
    // }

    public function createQuestion(array $data): int
    {
        if (!isset($data['question_quizz'], $data['lvl_quizz'], $data['type_quizz'])) {
            throw new InvalidArgumentException('Missing required parameters.');
        }
        if (empty($data['question_quizz']) || empty($data['lvl_quizz']) || empty($data['type_quizz'])) {
            throw new RuntimeException('Required parameters cannot be empty.');
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

    public function updateQuestion(array $data, int $id): bool
    {
        if (!isset($data['question_quizz'], $data['lvl_quizz'], $data['type_quizz'])) {
            throw new InvalidArgumentException('Missing required parameters.');
        }
        if (empty($data['question_quizz']) || empty($data['lvl_quizz']) || empty($data['type_quizz'])) {
            throw new RuntimeException('Required parameters cannot be empty.');
        }
        $query = "UPDATE quizz SET question_quizz = :question_quizz, lvl_quizz = :lvl_quizz, type_quizz = :type_quizz WHERE id_quizz =:id_quizz";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(
            [
                "question_quizz" => $data['question_quizz'],
                "lvl_quizz" => $data['lvl_quizz'],
                "type_quizz" => $data['type_quizz'],
                'id_quizz' => $id
            ]
        );
        return ($stmt->rowCount() > 0);
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
