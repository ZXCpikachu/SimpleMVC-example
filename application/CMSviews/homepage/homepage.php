<?php 
    $id = array();
    $content = array();
    foreach ($results['articles'] as $article) { ?>
        <li class='<?php echo $article->id?>'>
            
            <h2>
                <span class="pubDate">
                    <?php echo date('j F', $article->publicationDate)?>
                </span>

                <a href="<?= \ItForFree\SimpleMVC\Router\WebRouter::link('Homepage/singleArticle') . '&articleId=' . $article->id ?>">
                    <?php echo htmlspecialchars( $article->title )?>
                </a>

                <?php if (isset($article->categoryId) && array_key_exists($article->categoryId, $results['categories'])) { ?>
                    <span class="category">
                        Категория
                        <a href="<?= \ItForFree\SimpleMVC\Router\WebRouter::link('Homepage/viewArticleCategory') . '&categoryId=' . $article->categoryId ?>">
                            <?php echo htmlspecialchars($results['categories'][$article->categoryId]->name) ?>
                        </a>
                    </span>
                <?php } else { ?>
                    <span class="category">
                        <?php echo "Без категории" ?>
                    </span>
                <?php } ?>


                <?php if (isset($article->subcategoryId) && array_key_exists($article->subcategoryId, $results['subcategories'])) { ?>
                    <span class="subcategory">
                        Подкатегория 
                        <a href="<?= \ItForFree\SimpleMVC\Router\WebRouter::link('Homepage/viewArticleSubcategory') . '&subcategoryId=' . $article->subcategoryId ?>">
                            <?php echo htmlspecialchars($results['subcategories'][$article->subcategoryId]->name)?>
                        </a>
                    </span>
                <?php } else { ?>
                    <span class="category">
                        <?php echo "Без подкатегории"?>
                    </span>
                <?php } ?>
                <?php if (isset($article->authors)) { ?>
                <span class="authors">
                    Авторы: 
                    <?php 
                    $total = count($article->authors);
                    $counter = 0;
                    foreach($article->authors as $key =>$author){ ?>
                        <a href="<?= \ItForFree\SimpleMVC\Router\WebRouter::link('Homepage/viewArticleAuthor') . '&author=' . $author ?>">
                            <?php echo $author;
                            $counter++;
                            if($counter != $total){
                                echo ', ';
                            }
                    } ?>
                    </a>
                </span>
                <?php } ?>
            </h2>
            
            <p class="summary"><?php echo htmlspecialchars($article->content)?></p>
            <img id="loader-identity" src="JS/ajax-loader.gif" alt="gif">
            <ul class="ajax-load">
                <li><a href="<?= \ItForFree\SimpleMVC\Router\WebRouter::link('Homepage/viewArticle') . '&articleId=' . $article->id ?>" class="ajaxArticleBodyByPost" data-contentId="<?php echo $article->id?>">Показать продолжение (POST)</a></li>
                <li><a href="<?= \ItForFree\SimpleMVC\Router\WebRouter::link('Homepage/viewArticle') . '&articleId=' . $article->id ?>" class="ajaxArticleBodyByGet" data-contentId="<?php echo $article->id?>">Показать продолжение (GET)</a></li>
                <li><a href="<?= \ItForFree\SimpleMVC\Router\WebRouter::link('Homepage/viewArticle') . '&articleId=' . $article->id ?>" class="loadArticle" style="cursor:pointer" data-contentId="<?=$article->id?>">(POST) -- NEW</a></li>
                <li><a href="<?= \ItForFree\SimpleMVC\Router\WebRouter::link('Homepage/viewArticle') . '&articleId=' . $article->id ?>" class="loadArticle" style="cursor:pointer" data-contentId="<?=$article->id?>">(GET)  -- NEW</a></li>
            </ul>

            <a href="<?= \ItForFree\SimpleMVC\Router\WebRouter::link('Homepage/viewArticle') . '&articleId=' . $article->id ?>" class="showContent" data-contentId="<?php echo $article->id?>">Показать полностью</a>
        </li>
    <?php } ?>
</ul>
<p><a href="<?= \ItForFree\SimpleMVC\Router\WebRouter::link('Homepage/archive') ?>">Article Archive</a></p>
