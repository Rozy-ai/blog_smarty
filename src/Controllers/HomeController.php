<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Repositories\CategoryRepository;

final class HomeController
{
    public function __construct(
        private readonly View $view,
        private readonly CategoryRepository $categories
    ) {
    }

    public function index(): void
    {
        $categories = $this->categories->withLatestPosts(3);
        $this->view->render('home.tpl', ['categories' => $categories]);
    }
}
