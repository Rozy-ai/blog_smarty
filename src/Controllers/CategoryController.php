<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;

final class CategoryController
{
    public function __construct(
        private readonly View $view,
        private readonly CategoryRepository $categories,
        private readonly ArticleRepository $articles
    ) {
    }

    public function show(string $id): void
    {
        $category = $this->categories->find((int) $id);
        if (!$category) {
            http_response_code(404);
            echo 'Category not found';
            return;
        }

        $sort = $_GET['sort'] ?? 'date';
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 6;

        $result = $this->articles->byCategory((int) $id, $sort, $page, $perPage);
        $totalPages = max(1, (int) ceil($result['total'] / $perPage));

        $this->view->render('category.tpl', [
            'category' => $category,
            'articles' => $result['items'],
            'sort' => $sort,
            'page' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}
