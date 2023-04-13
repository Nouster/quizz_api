<?php

namespace App\crud;

use InvalidArgumentException;
use PDO;
use RuntimeException;

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
        return $collections ?: [];
    }

    public function retrieveOne(int $id): ?array
    {
        if ($id === 0) {
            throw new InvalidArgumentException("The specified ID is not valid.");
        }
        $query = "SELECT * FROM $this->table WHERE {$this->column[0]} = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        $item = $stmt->fetch();
        return $item ?: null;
    }

    public function createItem(array $data): int
    {
        if (!isset($data[$this->column[1]], $data[$this->column[2]], $data[$this->column[3]])) {
            throw new InvalidArgumentException('Missing required parameters.');
        }
        if (empty($data[$this->column[1]]) || empty($data[$this->column[2]]) || empty($data[$this->column[3]])) {
            throw new RuntimeException('Required parameters cannot be empty.');
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

}
