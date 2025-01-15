<?php print_r($results['authors'])?>
<h3 class="subcategoryDescription">Статьи автора <?php 
                    echo htmlspecialchars($results['authors']->login)?></h3>
    <ul id="headlines" class="archive">
        
        <?php foreach ($results['articles'] as $article) {?>
         <li>
             <h2>
                 <span class="pubDate">
                     <?php echo date('j F Y', $article->publicationDate)?>
                 </span>
                 <a href="<?= \ItForFree\SimpleMVC\Router\WebRouter::link('Homepage/singleArticle') . '&articleId=' . $article->id ?>">
                     <?php echo htmlspecialchars($article->title)?>
                 </a>
             </h2>
             <p class="summary"><?php echo htmlspecialchars($article->summary)?> </p>
         <li>
        <?php } ?>
    </ul>
    <p><?php echo $results['totalRows']?> article<?php 
                   echo ($results['totalRows'] != 1) ? 's' : '' ?> in total.</p>
    <p><a href="./">Return to Homepage</a></p>

