<h1>All Articles</h1>

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>


    <?php if ( isset( $results['statusMessage'] ) ) { ?>
            <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
    <?php } ?>

          <table>
            <tr>
              <th>Publication Date</th>
              <th>Article</th>
              <th>Category</th>
              <th>Subcategory</th>
              <th>Authors</th>
               <th>Active</th>
            </tr>

<!--<?php echo "<pre>"; print_r ($results['articles'][2]->publicationDate); echo "</pre>"; ?> Обращаемся к дате массива $results. Дата = 0 -->
            
    <?php foreach ( $results['articles'] as $article ) { ?>

            <tr onclick="location='<?=\ItForFree\SimpleMVC\Url::link('Admin/editArticle')?>&amp;articleId = <?php echo $article->id ?>'">
              <td><?php echo date('j M Y', $article->publicationDate)?></td>
              <td>
                <?php echo $article->title?>
              </td>
              <td>
                  
             <!--   <?php echo $results['categories'][$article->categoryId]->name?> Эта строка была скопирована с сайта-->
             <!-- <?php echo "<pre>"; print_r ($article); echo "</pre>"; ?> Здесь объект $article содержит в себе только ID категории. А надо по ID достать название категории-->
            <!--<?php echo "<pre>"; print_r ($results); echo "</pre>"; ?> Здесь есть доступ к полному объекту $results -->
             
                <?php 
                if(isset ($article->categoryId)) {
                    echo $results['categories'][$article->categoryId]->name;                        
                }
                else {
                echo "Без категории";
                }?>
              </td>
              <td>
                  <?php
                  if (isset ($article->subcategoryId)){
                      echo $results['subcategories'][$article->subcategoryId]->name;
                  }
                  ?>
              </td>
              <td>
                  <?php
                  $total = count($article->authors);
                    $counter = 0;
                    foreach ($article->authors as $key => $author) {
                ?>
                        <a href=".?action=viewArticleAuthor&amp;author=<?php 
                                                               echo $author ?>">
                        <?php
                            echo $author;
                            $counter++;
                            if ($counter != $total) {
                                echo ', ';
                            }
                  }
                  ?>
              </td>
              <td>
                  <?php
                    if($article->activeArticle) {
                        echo 'Active';
                    } else {
                        echo 'Not active';
                    }
                  ?>
              </td>
            </tr>

    <?php } ?>

          </table>

          <p><?php echo $results['totalRows']?> article<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> in total.</p>

          <p><a href="<?= \ItForFree\SimpleMVC\Url::link('Admin/newArticle') ?>">Add a New Article</a></p>

         