<?php

declare(strict_types=1);

use App\Core\Database;
use App\Core\Env;

require_once dirname(__DIR__) . '/vendor/autoload.php';

Env::load(dirname(__DIR__) . '/.env');
$config = require dirname(__DIR__) . '/src/Config/config.php';

$bootstrapPdo = new \PDO(
    sprintf(
        'mysql:host=%s;port=%d;charset=utf8mb4',
        $config['db']['host'],
        $config['db']['port']
    ),
    $config['db']['user'],
    $config['db']['password'],
    [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
);
$bootstrapPdo->exec(
    sprintf(
        'CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
        $config['db']['name']
    )
);

$pdo = Database::make($config['db']);

$schema = file_get_contents(__DIR__ . '/schema.sql');
$pdo->exec($schema);

$pdo->exec('SET FOREIGN_KEY_CHECKS=0');
$pdo->exec('TRUNCATE TABLE article_categories');
$pdo->exec('TRUNCATE TABLE articles');
$pdo->exec('TRUNCATE TABLE categories');
$pdo->exec('SET FOREIGN_KEY_CHECKS=1');

$categories = [
    ['PHP', 'Материалы по PHP и backend разработке'],
    ['JavaScript', 'Статьи по JS, браузеру и фронтенду'],
    ['MySQL', 'Практика работы с MySQL и SQL'],
];

$categoryStmt = $pdo->prepare('INSERT INTO categories (name, description) VALUES (?, ?)');
foreach ($categories as $category) {
    $categoryStmt->execute($category);
}

$articles = [
    [
        'https://picsum.photos/800/400?random=1',
        'Как устроен роутер на чистом PHP',
        'Разбираем простой роутер без фреймворков.',
        'Полный текст статьи про роутинг на чистом PHP.',
        25,
        '2026-01-10 10:00:00',
        [1],
    ],
    [
        'https://picsum.photos/800/400?random=2',
        'Smarty: зачем нужен шаблонизатор',
        'Плюсы отделения логики и представления.',
        'Полный текст статьи про Smarty шаблоны.',
        37,
        '2026-02-05 09:00:00',
        [1, 2],
    ],
    [
        'https://picsum.photos/800/400?random=3',
        '10 полезных приемов JavaScript',
        'Подборка техник для практики в JS.',
        'Полный текст статьи с советами по JavaScript.',
        72,
        '2026-03-01 14:30:00',
        [2],
    ],
    [
        'https://picsum.photos/800/400?random=4',
        'Оптимизация SQL-запросов',
        'Как писать быстрые запросы и индексы.',
        'Полный текст статьи про оптимизацию SQL.',
        51,
        '2026-03-20 17:00:00',
        [3],
    ],
    [
        'https://picsum.photos/800/400?random=5',
        'Связи многие-ко-многим в БД',
        'Разбираем таблицу связей на примере блога.',
        'Полный текст статьи про многие-ко-многим.',
        43,
        '2026-04-10 12:00:00',
        [1, 3],
    ],
    [
        'https://picsum.photos/800/400?random=6',
        'Пагинация и сортировка в MySQL',
        'Практический пример для страницы категории.',
        'Полный текст статьи о пагинации и сортировке.',
        19,
        '2026-04-28 11:15:00',
        [3],
    ],
];

$articleStmt = $pdo->prepare(
    'INSERT INTO articles (image, title, description, body, views, created_at) VALUES (?, ?, ?, ?, ?, ?)'
);
$pivotStmt = $pdo->prepare('INSERT INTO article_categories (article_id, category_id) VALUES (?, ?)');

foreach ($articles as $article) {
    [$image, $title, $description, $body, $views, $createdAt, $categoryIds] = $article;
    $articleStmt->execute([$image, $title, $description, $body, $views, $createdAt]);
    $articleId = (int) $pdo->lastInsertId();

    foreach ($categoryIds as $categoryId) {
        $pivotStmt->execute([$articleId, $categoryId]);
    }
}

echo "Seeding completed.\n";
