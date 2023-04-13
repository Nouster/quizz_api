<?php
namespace App\crud;
use PDO;

abstract class Crud 
{
    protected string $column;
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

}