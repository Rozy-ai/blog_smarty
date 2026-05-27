<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title|default:"Блог"}</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <a class="brand" href="/">PHP Blog Test Task</a>
        </div>
    </header>

    <main class="container">
        {block name=content}{/block}
    </main>
</body>
</html>
