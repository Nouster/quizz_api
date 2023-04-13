<?php

namespace App\crud;

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
        return $collections ?: [];
    }

    public function retrieveOne(int $id): ?array
    {
        $query = "SELECT * FROM $this->table WHERE $this->column[0] = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        $item = $stmt->fetch();
        return $item ?? null;
    }
}
