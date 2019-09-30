#!/usr/bin/php-cgi
<?php
session_start();
$localPrefix = "closed/php/";
require($localPrefix . "functions_comments.php");
include($localPrefix . "functions.php");

$article = new ArticleInfo();
$sqlite = new SQLiteClient();
$itemNum = isset($_GET['n']) ? $_GET['n'] : "0";
$pageKind = $itemNum == 100 ? "recommended" : "recent";
if ($pageKind == "recent") {
    $rss = new SimpleXMLElement(file_get_contents("rss.xml"));
    $item = $rss->channel->item[intval($itemNum)];
    $article->id = MyArticleUtils::getEntryFromUrl($item->link);
    $article->url = MyFileUtils::findArticlePath($article->id);
    $article->pubDate = date("Y.m.d H:i:s", strtotime($item->pubDate));
    $article->category = substr($article->url, 0, strrpos($article->url, "/"));
    $article->category = substr($article->category, strlen("closed/articles/"));
} else if ($pageKind == "recommended") {
    $article->id = $_GET['entry'];
    $article->url = MyFileUtils::findArticlePath($article->id);
    $article->category = substr($article->url, strlen("closed/articles/"));
    $article->category = substr($article->category, 0, strrpos($article->category, "/"));
}
?><!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="PICS-Label" content='(PICS-1.1 "http://www.classify.org/safesurf/" L gen true for "https://gorigorilinux.com" r (SS~~000 1 SS~~000 1))' />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="https://gorigorilinux.net/favicon.png" />
        <title><?php print($siteTitle); ?></title>
        <?php include("closed/php/css.php"); ?>
    </head>
    <body>
        <?php include($localPrefix . "header.php"); ?>
        <div class="contents">
            <div class="leftpane">
                <?php include("latest-articles.php"); ?>
		<?php include("about-this-site-list.html"); ?>
            </div>
            <div class="rightpane">
                <?php MyHTMLUtils::putHeaderMenu(2); ?>
                <?php if ($pageKind != "recommended") { ?>
                    <div class="pagenavi">
                        <?php if ($itemNum > 0) { ?>
                            <div class="left_float max_width_30per">
                                <a href="<?php echo "p" . ($itemNum - 1);?>">
                                    <img src="images/next-page-left.svg">
                                </a>
                            </div>
                        <?php } ?>
                        <?php if ($itemNum < 20) { ?>
                            <div class="right_float max_width_30per">
                                <a href="<?php echo "p" . ($itemNum + 1); ?>">
                                    <img src="images/prev-page-right.svg">
                                </a>
                            </div>
                        <?php } ?>                  
                        <div style="clear:both;"></div>
                    </div>
                <?php } else { ?>
                    <br>
                <?php } ?>
                <div class="article">
                    <div class="datetime">
                        <span class="mtime">
                            <?php if ($pageKind != "recommended") { ?>
                                <img src="images/time.svg">公開:<?=$itemPubDate?>
                            <?php } ?>
                            <img src="images/update.png">更新:<?=MyFileUtils::getFilemtime($article->url)?>
                        </span>
                        <div class="nav">
                            <div class="latest_article_lite">
                                <?php MyHTMLUtils::putArticleCategory($article->category); ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    $articleExtension = substr($article->url, strrpos($article->url, ".") + 1);
                    if ($articleExtension == "md") {
                        MyMarkdown::putMarkdown($article->url);
                    } else {
                        if ($article->url != null) {
                            include($article->url);
                        }
                    }
                    ?>
                </div>
                <?php if ($pageKind != "recommended") { ?>
                    <div class="pagenavi">
                        <?php if ($itemNum > 0) { ?>
                            <div class="left_float max_width_30per">
                                <a href="<?php echo "p" . ($itemNum - 1);?>">
                                    <img src="images/next-page-left.svg">
                                </a>
                            </div>
                        <?php } ?>
                        <?php if ($itemNum < 20) { ?>
                            <div class="right_float max_width_30per">
                                <a href="<?php echo "p" . ($itemNum + 1); ?>">
                                    <img src="images/prev-page-right.svg">
                                </a>
                            </div>
                        <?php } ?>                  
                        <div style="clear:both;"></div>
                    </div>
                <?php } ?>
            </div>
            <div style="clear:both;"></div>
        </div>
        <?php include($localPrefix . "footer.php"); ?>
    </body>
</html>

