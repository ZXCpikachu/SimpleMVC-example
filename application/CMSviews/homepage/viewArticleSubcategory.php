<?php 

if ($results['subcategory']) { ?>
    <h3 class="subcategoryDescription">
        <?php echo htmlspecialchars($results['subcategory']->description) ?>
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

                <?php if (!$results['subcategory'] && $article->subcategoryId && isset($results['subcategories'][$article->subcategoryId])) { ?>
                    <span class="subcategory">
                        in
                        <a href="<?= \ItForFree\SimpleMVC\Router\WebRouter::link('Homepage/archive') . '&subcategoryId=' . $article->subcategoryId ?>">
                            <?php echo htmlspecialchars($results['subcategories'][$article->subcategoryId]->name) ?>
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