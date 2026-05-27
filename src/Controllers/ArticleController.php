<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Repositories\ArticleRepository;

final class ArticleController
{
    public function __construct(
        private readonly View $view,
        private readonly ArticleRepository $articles
    ) {
    }

    public function show(string $id): void
    {
        $articleId = (int) $id;
        $this->articles->incrementViews($articleId);
        $article = $this->articles->findWithCategories($articleId);

        if (!$article) {
            http_response_code(404);
            echo 'Article not found';
            return;
        }

        $categoryIds = array_map(
            static fn (array $cat): int => (int) $cat['id'],
            $article['categories']
        );
        $similar = $this->articles->similarByCategoryIds($categoryIds, $articleId, 3);

        $this->view->render('article.tpl', [
            'article' => $article,
            'similarArticles' => $similar,
        ]);
    }
}
