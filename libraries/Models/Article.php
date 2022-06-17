<?php

namespace Models;

class Article extends Model
{
    protected $table = "posts";

    public function insert(string $author, string $content, string $title, string $image, string $category): void
    {
        $query = $this->pdo->prepare("INSERT INTO $this->table SET author = :author, content = :content, title = :title, image = :image, category = :category, creation_date = NOW()");
        $query->execute(compact('author', 'content', 'title', 'image', 'category'));
    }
}
