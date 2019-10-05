<?php
if (!isset($rss)) {
    $rss = new SimpleXMLElement(file_get_contents("rss.xml"));
    $item = $rss->channel->item[intval($itemNum)];
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
        $mini->category = substr($mini->category, 0, strrpos($mini->category, "/"));
	$mini->category = substr($mini->category, strlen("closed/articles/"));
	if (array_key_exists($mini->id, $commentsCount)) {
	    $count = $commentsCount[$mini->id];
	} else {
	    $count = 0;
	}
	$mini->url = 'p' . $i;
        ?>
        <div class="latest_article_lite">
            <h4>
		<span class="category_article">
		    <?php MyHTMLUtils::putArticleCategorySingle($mini->category); ?>
		</span>
                <a href="<?=$mini->url?>">
		    <?=$mini->title?>
		    <span class="date_article"><?=$mini->pubDate?></span>
		    <?php if ($count > 0) { ?>
			<span class="comments_count">コメント: <?=$count?></span>
		    <?php } ?>
		</a>
            </h4>
        </div>
	<?php
	$i++;
	if ($i >= $latestArticlesMaxSize) {
	    break;
	}
	?>
    <?php } ?>
</div>
