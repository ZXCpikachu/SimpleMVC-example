<h1 style="width: 75%;"><?php echo htmlspecialchars($results['article']['title']) ?></h1>
<div style="width: 75%; font-style: italic;"><?php echo htmlspecialchars($results['article']['summary']) ?></div>
<div style="width: 75%;"><?php echo $results['article']['content'] ?></div>
<p>Subcategory: <?= htmlspecialchars($results['article']['subcategory']) ?></p>
<div style="width: 75%;">
    Authors: 
    <?= !empty($results['article']['authors']) 
        ? htmlspecialchars($results['article']['authors']) 
        : "No authors available." ?>
</div>

<p class="pubDate">Published on <?php echo date('j F Y', strtotime($results['article']['publicationDate'])); ?></p>

</p>

<p><a href="<?= \ItForFree\SimpleMVC\Router\WebRouter::link('Homepage/index') ?>">Return to Homepage</a></p>