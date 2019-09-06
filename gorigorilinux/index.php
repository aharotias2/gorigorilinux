#!/usr/bin/php-cgi
<?php
session_start();
$localPrefix = "closed/php/";
require($localPrefix . "functions_comments.php");
require_once($localPrefix . "functions_markdown.php");
$sqlite = new SQLiteClient();
include($localPrefix . "functions.php");
$itemNum = isset($_GET['n']) ? $_GET['n'] : "0";
$pageKind = $itemNum == 100 ? "recommended" : "recent";
if ($pageKind == "recent") {
    $rss = new SimpleXMLElement(file_get_contents("rss.xml"));
    $item = $rss->channel->item[intval($itemNum)];
    $articleUrl = ttFindPath("closed/articles/" . substr($item->link, strpos($item->link, "=") + 1));
    $itemPubDate = date("Y.m.d H:i:s", strtotime($item->pubDate));
} else if ($pageKind == "recommended") {
    $articleUrl = ttFindPath("closed/articles/" . $_GET['entry']);
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
            <div class="rightpane">
                <?php ttPutHeaderMenu(2); ?>
                <?php if ($pageKind != "recommended") { ?>
                    <div class="pagenavi">
                        <?php if ($itemNum > 0) { ?>
                            <div class="left_float max_width_30per">
                                <a href="<?php echo "index.php?n=" . ($itemNum - 1);?>">
                                    <img src="images/next-page-left.svg">
                                </a>
                            </div>
                        <?php } ?>
                        <?php if ($itemNum < 20) { ?>
                            <div class="right_float max_width_30per">
                                <a href="<?php echo "index.php?n=" . ($itemNum + 1); ?>">
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
                            <img src="images/update.png">更新:<?=ttGetFilemtime($articleUrl)?>
                        </span>
                    </div>
                    <?php
                    $articleExtension = substr($articleUrl, strrpos($articleUrl, ".") + 1);
                    if ($articleExtension == "md") {
                        ttPutMarkdown($articleUrl);
                    } else {
                        include($articleUrl);
                    }
                    ?>
                </div>
                <?php if ($pageKind != "recommended") { ?>
                    <div class="pagenavi">
                        <?php if ($itemNum > 0) { ?>
                            <div class="left_float max_width_30per">
                                <a href="<?php echo "index.php?n=" . ($itemNum - 1);?>">
                                    <img src="images/next-page-left.svg">
                                </a>
                            </div>
                        <?php } ?>
                        <?php if ($itemNum < 20) { ?>
                            <div class="right_float max_width_30per">
                                <a href="<?php echo "index.php?n=" . ($itemNum + 1); ?>">
                                    <img src="images/prev-page-right.svg">
                                </a>
                            </div>
                        <?php } ?>                  
                        <div style="clear:both;"></div>
                    </div>
                <?php } ?>
            </div>
            <div class="leftpane">
		<?php include("latest-articles.php"); ?>
                <div class="nav">
                    <h3>案内記事</h3>
                    <ul>
                        <li>
                            <ul>
                                <li><a href="index.php?n=100&entry=misc/self/01_self/001">このサイトについて</a></li>
                                <li><a href="index.php?n=100&entry=misc/self/01_self/002">管理人について</a></li>
                                <li><a href="index.php?n=100&entry=misc/self/01_self/003">コメント欄のマークダウンの書き方</a></li>
                                <li><a href="index.php?n=100&entry=misc/self/01_self/004">TODOリスト</a></li>
                                <li><a href="index.php?n=100&entry=misc/self/01_self/005">お気に入りリンク集</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            <div style="clear:both;"></div>
        </div>
        <?php include($localPrefix . "footer.php"); ?>
    </body>
</html>

