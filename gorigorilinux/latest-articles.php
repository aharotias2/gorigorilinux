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
        $entry = MyArticleUtils::getEntryFromUrl($item->link);
	$articlePath = MyFileUtils::findArticlePath($entry);
        $articleTitle = $item->title;
	$articleName = $entry;
        $pubDate = date("Y.m.d", strtotime($item->pubDate));
        $category = substr($articlePath, 0, strrpos($articlePath, "/"));
	$category = substr($category, strlen("closed/articles/"));
	if (array_key_exists($articleName, $commentsCount)) {
	    $count = $commentsCount[$item->comment];
	} else {
	    $count = 0;
	}
	$articleUrl = MyArticleUtils::getUrlFromEntry($articleName);
        ?>
        <div class="latest_article_lite">
            <h4>
                <a href="<?=$articleUrl?>">
		    <?=$articleTitle?>
		    <span class="date_article"><?=$pubDate?></span>
                    <span class="comments_count">コメント: <?=$count?></span>
		</a>
            </h4>
	    <?php MyHTMLUtils::putArticleCategory($category); ?>
        </div>
	<?php
	$i++;
	if ($i >= $latestArticlesMaxSize) {
	    break;
	}
	?>
    <?php } ?>
</div>
