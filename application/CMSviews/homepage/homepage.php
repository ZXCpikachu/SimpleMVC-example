<ul id="headlines">
    <?php 
    $id = array();
    $content = array();
    foreach ($results['articles'] as $article){ ?>
        <li class='<?php echo $article->id?>'>
            <h2>
                <span class="pubDate">
                    <?php echo date('j F', $article->publicationDate)?>
                </span>

                <a href="<?= \ItForFree\SimpleMVC\Url::link('CMSHomepage/viewArticle')?>&amp;articleId=<?php echo $article->id?>">
                    <?php echo htmlspecialchars( $article->title )?>
                </a>

                <?php if (isset($article->categoryId) && isset($results['categories'][$article->categoryId])) { ?>
                    <span class="category">
                        Категория
                        <a href="<?= \ItForFree\SimpleMVC\Url::link('CMSHomepage/archiveCat')?>&amp;categoryId=<?php echo $article->categoryId?>">
                            <?php echo htmlspecialchars($results['categories'][$article->categoryId]->name)?>
                        </a>
                    </span>
                <?php } else { ?>
                    <span class="category">
                        <?php echo "Без категории"?>
                    </span>
                <?php } ?>

                <?php if (isset($article->subcategoryId) && isset($results['subcategories'][$article->subcategoryId])) { ?>
                    <span class="category">
                        Подкатегория 
                        <a href="<?= \ItForFree\SimpleMVC\Url::link('CMSHomepage/archiveSubcat')?>&amp;subcategoryId=<?php echo $article->subcategoryId?>"
                            <?php echo htmlspecialchars($results['subcategories'][$article->subcategoryId]->name)?>
                        </a>
                    </span>
                <?php } else { ?>
                    <span class="category">
                        <?php echo "Без подкатегории"?>
                    </span>
                <?php } ?>
                 <?php if (isset($article->authors)) { ?>
                <span class="category">
                    Авторы: 
                    <?php 
                    $total = count($article->authors);
                    $counter = 0;
                    foreach($article->authors as $key =>$author){ ?>
                        <a href="<?= \ItForFree\SimpleMVC\Url::link('CMSHomepage/viewArticleAuthor')?>&amp;userId=<?php
                                                                echo $author?>">
                            <?php echo $author;
                            $counter++;
                            if($counter != $total){
                                echo ', ';
                            }
                    }?>
                    </a><?php } ?>
                </span>
            </h2>
            
            <p class="summary"><?php echo htmlspecialchars($article->content50char)?></p>
            <img id="loader-identity" src="JS/ajax-loader.gif" alt="gif">
            <ul class="ajax-load">
                       <li><a href="<?= \ItForFree\SimpleMVC\Url::link('CMSHomepage/viewArticle')?>&amp;articleId=<?php echo $article->id?>" class="ajaxArticleBodyByPost" data-contentId="<?php echo $article->id?>">Показать продолжение (POST)</a></li>
                <li><a href="<?= \ItForFree\SimpleMVC\Url::link('CMSHomepage/viewArticle')?>&amp;articleId=<?php echo $article->id?>" class="ajaxArticleBodyByGet" data-contentId="<?php echo $article->id?>">Показать продолжение (GET)</a></li>
                <li><a href="<?= \ItForFree\SimpleMVC\Url::link('CMSHomepage/viewArticle')?>&amp;articleId=<?php echo $article->id?>" class="">(POST) -- NEW</a></li>
                <li><a href="<?= \ItForFree\SimpleMVC\Url::link('CMSHomepage/viewArticle')?>&amp;articleId=<?php echo $article->id?>" class="">(GET)  -- NEW</a></li>
            </ul>

                <a href="<?= \ItForFree\SimpleMVC\Url::link('CMSHomepage/viewArticle')?>&amp;articleId=<?php echo $article->id?>" class="showContent" data-contentId="<?php echo $article->id?>">Показать полностью</a>
        </li>
    <?php } ?>
</ul>
   <p><a href="<?= \ItForFree\SimpleMVC\Url::link('CMSHomepage/archive')?>">
           Article Archive
       </a>
   </p>


