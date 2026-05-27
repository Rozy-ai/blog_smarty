{extends file="layouts/base.tpl"}
{block name=content}
    <h1>{$category.name|escape}</h1>
    <p>{$category.description|escape}</p>

    <form method="get" class="sort-form">
        <label for="sort">Сортировка:</label>
        <select name="sort" id="sort" onchange="this.form.submit()">
            <option value="date" {if $sort == 'date'}selected{/if}>По дате</option>
            <option value="views" {if $sort == 'views'}selected{/if}>По просмотрам</option>
        </select>
    </form>

    <div class="cards">
        {foreach $articles as $article}
            <article class="card">
                <img src="{$article.image|escape}" alt="{$article.title|escape}">
                <h3><a href="/article/{$article.id}">{$article.title|escape}</a></h3>
                <p>{$article.description|escape}</p>
                <small>Просмотры: {$article.views}</small>
            </article>
        {/foreach}
    </div>

    <div class="pagination">
        {section name=p loop=$totalPages}
            {$pageNum = $smarty.section.p.index + 1}
            <a href="?sort={$sort}&page={$pageNum}" class="{if $pageNum == $page}active{/if}">
                {$pageNum}
            </a>
        {/section}
    </div>
{/block}
