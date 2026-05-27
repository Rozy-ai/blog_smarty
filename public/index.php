<?php

declare(strict_types=1);

use App\Controllers\ArticleController;
use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Core\Database;
use App\Core\Env;
use App\Core\Router;
use App\Core\View;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;

require_once dirname(__DIR__) . '/vendor/autoload.php';

Env::load(dirname(__DIR__) . '/.env');
$config = require dirname(__DIR__) . '/src/Config/config.php';

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

$pdo = Database::make($config['db']);
$view = new View($config['paths']);

$categoryRepository = new CategoryRepository($pdo);
$articleRepository = new ArticleRepository($pdo);

$homeController = new HomeController($view, $categoryRepository);
$categoryController = new CategoryController($view, $categoryRepository, $articleRepository);
$articleController = new ArticleController($view, $articleRepository);

$router = new Router();
$router->get('/', [$homeController, 'index']);
$router->get('/category/{id}', [$categoryController, 'show']);
$router->get('/article/{id}', [$articleController, 'show']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
