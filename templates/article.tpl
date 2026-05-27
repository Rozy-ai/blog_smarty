{extends file="layouts/base.tpl"}
{block name=content}
    <article class="article-page">
        <h1>{$article.title|escape}</h1>
        <img class="hero" src="{$article.image|escape}" alt="{$article.title|escape}">
        <p>{$article.description|escape}</p>
        <div class="meta">
            <span>Просмотры: {$article.views}</span>
            <span>
                Категории:
                {foreach $article.categories as $cat}
                    <a href="/category/{$cat.id}">{$cat.name|escape}</a>{if !$cat@last}, {/if}
                {/foreach}
            </span>
        </div>
        <div class="body-text">{$article.body|escape|nl2br nofilter}</div>
    </article>

    <section class="section">
        <h2>Похожие статьи</h2>
        <div class="cards">
            {foreach $similarArticles as $similar}
                <article class="card">
                    <img src="{$similar.image|escape}" alt="{$similar.title|escape}">
                    <h3><a href="/article/{$similar.id}">{$similar.title|escape}</a></h3>
                    <p>{$similar.description|escape}</p>
                </article>
            {/foreach}
        </div>
    </section>
{/block}
