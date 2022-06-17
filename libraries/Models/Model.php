<?php

namespace Models;

use Database;

abstract class Model
{
    protected $pdo;
    protected $table;

    public function __construct()
    {
        $this->pdo = Database::getPdo();
    }

    public function findAll(string $category): array
    {
        $query = $this->pdo->prepare("SELECT * FROM $this->table WHERE category = :category ORDER BY creation_date DESC");
        $query->execute(['category' => $category]);
        $posts = $query->fetchAll();

        return $posts;
    }

    public function findLasts(): array
    {
        $query = $this->pdo->prepare("SELECT * FROM $this->table ORDER BY creation_date DESC LIMIT 4");
        $query->execute();
        $posts = $query->fetchAll();

        return $posts;
    }

    public function find(int $id)
    {
        $query = $this->pdo->prepare("SELECT * FROM $this->table WHERE id = :id");
        $query->execute(['id' => $id]);

        $item = $query->fetch();

        return $item;
    }
}
