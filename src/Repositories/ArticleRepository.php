<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class ArticleRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function byCategory(int $categoryId, string $sort, int $page, int $perPage): array
    {
        $allowedSort = [
            'date' => 'a.created_at DESC',
            'views' => 'a.views DESC',
        ];
        $orderBy = $allowedSort[$sort] ?? $allowedSort['date'];
        $offset = max(0, ($page - 1) * $perPage);

        $countStmt = $this->pdo->prepare(
            'SELECT COUNT(DISTINCT a.id) as total
             FROM articles a
             JOIN article_categories ac ON ac.article_id = a.id
             WHERE ac.category_id = :category_id'
        );
        $countStmt->execute(['category_id' => $categoryId]);
        $total = (int) ($countStmt->fetch()['total'] ?? 0);

        $stmt = $this->pdo->prepare(
            "SELECT DISTINCT a.id, a.title, a.description, a.image, a.created_at, a.views
             FROM articles a
             JOIN article_categories ac ON ac.article_id = a.id
             WHERE ac.category_id = :category_id
             ORDER BY {$orderBy}
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'items' => $stmt->fetchAll(),
            'total' => $total,
        ];
    }

    public function findWithCategories(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, title, description, body, image, views, created_at
             FROM articles
             WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);
        $article = $stmt->fetch();

        if (!$article) {
            return null;
        }

        $catStmt = $this->pdo->prepare(
            'SELECT c.id, c.name
             FROM categories c
             JOIN article_categories ac ON ac.category_id = c.id
             WHERE ac.article_id = :article_id'
        );
        $catStmt->execute(['article_id' => $id]);
        $article['categories'] = $catStmt->fetchAll();

        return $article;
    }

    public function incrementViews(int $id): void
    {
        $stmt = $this->pdo->prepare('UPDATE articles SET views = views + 1 WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function similarByCategoryIds(array $categoryIds, int $excludeId, int $limit = 3): array
    {
        if ($categoryIds === []) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));
        $sql = "SELECT DISTINCT a.id, a.title, a.description, a.image, a.created_at, a.views
                FROM articles a
                JOIN article_categories ac ON ac.article_id = a.id
                WHERE ac.category_id IN ({$placeholders})
                AND a.id != ?
                ORDER BY a.created_at DESC
                LIMIT {$limit}";

        $stmt = $this->pdo->prepare($sql);
        $params = [...$categoryIds, $excludeId];
        $stmt->execute($params);

        return $stmt->fetchAll();
    }
}
