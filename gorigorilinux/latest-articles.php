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
<div class="nav">
    <h3>最近の投稿</h3>
    <?php foreach ($rss->channel->item as $item) { ?>
        <?php
	$mini = new ArticleInfo();
        $mini->id = MyArticleUtils::getEntryFromUrl($item->link);
	$mini->path = MyFileUtils::findArticlePath($mini->id);
        $mini->title = $item->title;
        $mini->pubDate = date("Y.m.d", strtotime($item->pubDate));
        $mini->category = substr($mini->path, 0, strrpos($mini->path, "/"));
	$mini->category = substr($mini->category, strlen("closed/articles/"));
	if (array_key_exists($article->id, $commentsCount)) {
	    $count = $commentsCount[$mini->id];
	} else {
	    $count = 0;
	}
	$mini->url = MyArticleUtils::getUrlFromEntry($mini->id);
        ?>
        <div class="latest_article_lite">
            <h4>
                <a href="<?=$mini->url?>">
		    <?=$mini->title?>
		    <span class="date_article"><?=$mini->pubDate?></span>
                    <span class="comments_count">コメント: <?=$count?></span>
		</a>
            </h4>
	    <?php MyHTMLUtils::putArticleCategory($mini->category); ?>
        </div>
	<?php
	$i++;
	if ($i >= $latestArticlesMaxSize) {
	    break;
	}
	?>
    <?php } ?>
</div>
