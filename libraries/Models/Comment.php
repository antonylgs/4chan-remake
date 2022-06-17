<?php

namespace Models;

class Comment extends Model
{
    protected $table = "comments";

    public function findAllInArticle(int $id): array
    {
        $query = $this->pdo->prepare("SELECT * FROM comments WHERE post_id = :post_id ORDER BY creation_date DESC");
        $query->execute(['post_id' => $id]);
        $commentaires = $query->fetchAll();

        return $commentaires;
    }

    public function insert(string $author, string $content, int $post_id, string $image): void
    {
        $query = $this->pdo->prepare('INSERT INTO comments SET author = :author, content = :content, post_id = :post_id, image = :image, creation_date = NOW()');
        $query->execute(compact('author', 'content', 'post_id', 'image'));
    }
}
