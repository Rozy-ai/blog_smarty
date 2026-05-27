{extends file="layouts/base.tpl"}
{block name=content}
    <h1>Категории с последними статьями</h1>

    {if !$categories}
        <p>Пока нет статей.</p>
    {/if}

    {foreach $categories as $category}
        <section class="section">
            <h2>{$category.name|escape}</h2>
            <p>{$category.description|escape}</p>

            <div class="cards">
                {foreach $category.posts as $post}
                    <article class="card">
                        <img src="{$post.image|escape}" alt="{$post.title|escape}">
                        <h3><a href="/article/{$post.id}">{$post.title|escape}</a></h3>
                        <p>{$post.description|escape}</p>
                        <small>Просмотры: {$post.views}</small>
                    </article>
                {/foreach}
            </div>

            <a class="button" href="/category/{$category.id}">Все статьи</a>
        </section>
    {/foreach}
{/block}
