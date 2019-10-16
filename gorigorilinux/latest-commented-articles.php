<?php
if (!isset($dao)) {
    $dao = new CommentsDao(null);
}
if ($dao != null) {
    $recentComments = $dao->getRecentComments(5);
}

$i = 0;
?>
<div class="nav">
    <h3>最近のコメント</h3>
    <?php foreach ($recentComments as $item) { ?>
        <?php
	$mini = new ArticleInfo();
        $mini->id = MyArticleUtils::getEntryFromUrl($item['article_name']);
	$mini->path = MyFileUtils::findArticlePath($mini->id);
        $mini->title = MyArticleUtils::getArticleTitle($mini->path);
        $mini->pubDate = $item['post_date'];
        $mini->category = substr($mini->path, 0, strrpos($mini->path, "/"));
	$mini->category = substr($mini->category, strlen("closed/articles/"));
	$mini->url = MyArticleUtils::getUrlFromEntry($mini->id);
        ?>
        <div class="latest_article_lite">
            <h4>
                <a href="<?=$mini->url?>">
		    <?=$mini->title?>
		    <span class="date_article"><?=$mini->pubDate?></span>
		    <?php if ($count > 0) { ?>
			<span class="comments_count">コメント: <?=$count?></span>
		    <?php } ?>
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
