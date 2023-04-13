<?php

namespace App\crud;

use App\exception\EmptyParameterException;
use App\exception\IdNotFoundException;
use InvalidArgumentException;
use PDO;

abstract class Crud
{
    protected array $column;
    protected string $table;

    public function __construct(protected PDO $pdo)
    {
    }

    public function retrieveAll(): array
    {
        $query = "SELECT * FROM $this->table";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $collections = $stmt->fetchAll();
        if(count($collections)===0){
            throw new EmptyParameterException("No data found");
        }
        return $collections ?: [];
    }

    public function retrieveOne(int $id): ?array
    {
        if ($id === 0) {
            throw new InvalidArgumentException("The specified ID is not valid.");
        }
        $query = "SELECT * FROM " . $this->table . " WHERE " . $this->column[0] . " = :id";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        $item = $stmt->fetch();
        // I can't catch exception if it's a triple equal to
        if($item == null) {
            throw new IdNotFoundException("The ID was not found.");
        }
        return $item ?: null;
    }

    public function createItem(array $data): int
    {
        if (!isset($data[$this->column[1]], $data[$this->column[2]], $data[$this->column[3]])) {
            throw new InvalidArgumentException('Missing required parameters.');
        }
        if (empty($data[$this->column[1]]) || empty($data[$this->column[2]]) || empty($data[$this->column[3]])) {
            throw new EmptyParameterException('Required parameters cannot be empty.');
        }
        $query = "INSERT INTO $this->table VALUES (null, :column_2, :column_3, :column_4)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'column_2' => $data[$this->column[1]],
            'column_3' => $data[$this->column[2]],
            'column_4' => $data[$this->column[3]],
        ]);
        return $this->pdo->lastInsertId();
    }

    public function updateItem(array $data, int $id): bool
    {
        if (!isset($data[$this->column[1]], $data[$this->column[2]], $data[$this->column[3]])) {
            throw new InvalidArgumentException('Missing required parameters.');
        }
        if (empty($data[$this->column[1]]) || empty($data[$this->column[2]]) || empty($data[$this->column[3]])) {
            throw new EmptyParameterException('Required parameters cannot be empty.');
        }

        $query = "UPDATE " . $this->table . " SET " . $this->column[1] . " = :column_2, " . $this->column[2] . " = :column_3, " . $this->column[3] . " = :column_4 WHERE " . $this->column[0] . " = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'column_2' => $data[$this->column[1]],
            'column_3' => $data[$this->column[2]],
            'column_4' => $data[$this->column[3]],
            'id' => $id,
        ]);
        return ($stmt->rowCount() > 0);
    }

    public function deleteItem(int $id): bool
    {
        if ($id === 0) {
            throw new InvalidArgumentException("The specified ID is not valid.");
        }
        $query = "DELETE FROM " . $this->table . " WHERE " . $this->column[0] . " = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        if (!$stmt->rowCount()) {
            throw new IdNotFoundException("The specified ID does not exist.");
        }
        return ($stmt->rowCount() > 0);
    }
}
