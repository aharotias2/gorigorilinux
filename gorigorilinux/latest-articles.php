<?php
if ($rss == null) {
    $rss = new SimpleXMLElement(file_get_contents("rss.xml"));
    $item = $rss->channel->item[intval($itemNum)];
}

$dao = new CommentsDao(null);
if ($dao != null) {
    $commentsCount = $dao->getCommentsCount();
}

$latestArticlesMaxSize = 10;
$i = 0;
?>
<div class="menu-header">
    <div class="menubutton"><img src="images/closemenubutton.svg"></div>
</div>
<div class="nav">
    <h3>最近の投稿</h3>
    <?php foreach ($rss->channel->item as $item) { ?>
        <?php
        $url = $item->link;
        $articleId = substr($item->link, strrpos($item->link, "=") + 1);
        $articleTitle = $item->title;
	$articleName = $item->comments;
        $pubDate = date("Y.m.d", strtotime($item->pubDate));
        $category = substr($articleId, 0, strrpos($articleId, "/"));
	if (array_key_exists($articleName, $commentsCount)) {
	    $count = $commentsCount[$item->comment];
	} else {
	    $count = 0;
	}
        ?>
        <div class="latest_article_lite">
            <h4>
                <a href="article.php?entry=<?=$articleId?>">
		    <?=$articleTitle?>
		    <span class="date_article"><?=$pubDate?></span>
                    <span class="comments_count">コメント: <?=$count?></span>
		</a>
            </h4>
            <div class="category_article">
                <?php $tmp = ""; ?>
                <?php foreach (explode("/", $category) as $catName) { ?>
                    <?php
                    $tmp = $tmp . $catName;
                    $categoryColor = getCategoryColor($catName);
                    $categoryNameJa = translate($catName);
                    ?>
                    <a href="toc.php?category=<?=$tmp?>">
                        <span class="category_article_piece" style="background-color:<?=$categoryColor?>">
                            <?=$categoryNameJa?>
                        </span>
                    </a>
                <?php } ?>
            </div>
        </div>
	<?php
	$i++;
	if ($i >= $latestArticlesMaxSize) {
	    break;
	}
	?>
    <?php } ?>
</div>
