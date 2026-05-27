<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class CategoryRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function withLatestPosts(int $limit = 3): array
    {
        $categories = $this->pdo->query(
            'SELECT DISTINCT c.id, c.name, c.description
             FROM categories c
             JOIN article_categories ac ON ac.category_id = c.id
             JOIN articles a ON a.id = ac.article_id
             ORDER BY c.name ASC'
        )->fetchAll();

        foreach ($categories as &$category) {
            $stmt = $this->pdo->prepare(
                'SELECT a.id, a.title, a.description, a.image, a.created_at, a.views
                 FROM articles a
                 JOIN article_categories ac ON ac.article_id = a.id
                 WHERE ac.category_id = :category_id
                 ORDER BY a.created_at DESC
                 LIMIT :limit'
            );
            $stmt->bindValue(':category_id', (int) $category['id'], PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $category['posts'] = $stmt->fetchAll();
        }

        return $categories;
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, name, description FROM categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }
}
