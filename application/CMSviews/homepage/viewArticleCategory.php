<?php 
if ($results['categories']) { ?>
    <h3 class="categoryDescription">
        <?php echo htmlspecialchars($results['categories']->description) ?>
    </h3>
<?php } ?>

<ul id="headlines" class="archive">
    <?php foreach ($results['articles'] as $article) { ?>
        <li>
            <h2>
                <span class="pubDate">
                    <?php echo date('j F Y', $article->publicationDate) ?>
                </span>
                <a href="<?= \ItForFree\SimpleMVC\Router\WebRouter::link('Homepage/singleArticle') . '&articleId=' . $article->id ?>">
                    <?php echo htmlspecialchars($article->title) ?>
                </a>

                <?php if (!$results['categories'] && $article->categoryId && isset($results['categories'][$article->categoryId])) { ?>
                    <span class="category">
                        in
                        <a href="<?= \ItForFree\SimpleMVC\Router\WebRouter::link('Homepage/archive') . '&categoryId=' . $article->categoryId ?>">
                            <?php echo htmlspecialchars($results['categories'][$article->categoryId]->name) ?>
                        </a>
                    </span>
                <?php } ?>
            </h2>
            <p class="summary"><?php echo htmlspecialchars($article->summary) ?></p>
        </li>
    <?php } ?>
</ul>

<p><?php echo $results['totalRows'] ?> article<?php echo ($results['totalRows'] == 1) ? '' : 's' ?> in total.</p>
<p><a href="<?= \ItForFree\SimpleMVC\Router\WebRouter::link('Homepage/index') ?>">Return to Homepage</a></p>