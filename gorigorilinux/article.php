#!/usr/bin/php-cgi
<?php
session_start();
require_once("closed/php/functions.php");
$sqlite = new SQLiteClient();
$entry = $_GET['entry'];
$articleUrl = MyFileUtils::findArticlePath($entry);
$category = substr(dirname($articleUrl), strlen("closed/articles/"));
$articleUrl = MyFileUtils::findArticlePath($entry);
$articleName = MyArticleUtils::getArticleName($articleUrl);
$articleTitle = MyArticleUtils::getArticleTitle($articleUrl);
$fullUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$parentCategory = MyArticleUtils::getParentCategory($category);
$now = new Datetime(date('Y/m/d H:i:s'));
if (!isset($_SESSION[$articleName]) || $now->sub(new DateInterval('PT24H')) > $_SESSION[$articleName]['time']) {
    $_SESSION[$articleName] = array('good' => 0, 'bad' => 0, 'time' => new Datetime(date('Y/m/d H:i:s')));
}
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="PICS-Label" content='(PICS-1.1 "http://www.classify.org/safesurf/" L gen true for "https://gorigorilinux.net" r (SS~~000 1 SS~~000 1))' />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="https://gorigorilinux.net/favicon.png" />
        <title><?php MyHTMLUtils::putArticleTitle($articleUrl); ?>: <?php echo $siteTitle; ?></title>
        <script type="text/javascript">
         articleName = "<?php echo $articleName;?>";
         session = {};
         session.good = <?php echo $_SESSION[$articleName]['good']; ?>;
         session.bad = <?php echo $_SESSION[$articleName]['bad']; ?>;
        </script>
        <?php include("closed/php/css.php"); ?>
    </head>
    <body>
        <?php include("closed/php/header.php"); ?>
        <div class="contents">
            <div class="rightpane">
		<?php MyHTMLUtils::putHeaderMenu(2); ?>
                <div class="pagenavi">
                    <?php MyHTMLUtils::putPrevLink($entry); MyHTMLUtils::putNextLink($entry); ?>
                    <div style="clear:both;"></div>
                </div>
                <div class="article">
                    <div class="datetime">
                        <span class="mtime">
                            <img src="images/time.svg" alt="最終更新日時:"><?=MyFileUtils::getFilemtime($articleUrl)?>
                        </span>
                    </div>
                    <?php
                    $articleExtension = substr($articleUrl, strrpos($articleUrl, ".") + 1);
                    if ($articleExtension == "md") {
                        MyMarkdown::putMarkdown($articleUrl);
                    } else {
                        include($articleUrl);
                    }
                    ?>
                </div>
                <div class="pagenavi">
                    <?php MyHTMLUtils::putPrevLink($entry); MyHTMLUtils::putNextLink($entry); ?>
                    <div style="clear:both;"></div>
                </div>
                <div class="comment_block">
                    <h4>評価・コメントをお残しください...</h4>
                    <div class="goodbad comment_switch">
                        <?php
                        $good = $_SESSION[$articleName]['good'];
                        $bad = $_SESSION[$articleName]['bad'];
                        $sqlite->putGoodbad($articleName, $good, $bad);
                        ?>
                        <button id="comment_switcher">コメントする</button>
                    </div>
                    <div class="post_block">
                        <form id="commentform" action="post.php" method="POST">
                            <p>コメント: <br><textarea rows="5" cols="80" id="comment_text" name="comment"></textarea></p>
                            <p>おなまえ: <br><input type="text" id="comment_user" name="user_name"></p>
                            <p>メール: <br><input type="text" id="comment_mail" name="mail_address"></p>
                            <p>
                                <input type="hidden" name="entry" value="<?php echo $entry ?>">
                                <input type="hidden" name="article_name" value="<?php echo $articleName ?>">
                                <input type="hidden" name="anchor" value="0">
                                <button id="comment_submit" name="submit">コメントする</button>
                                <button id="comment_cancel" name="comment_cancel">キャンセル</button>
                            </p>
                        </form>
                    </div>
                    <?php $sqlite->putComments($articleName); ?>
                </div>
            </div>
            <div class="leftpane">
                <div class="menu-header">
                    <div class="menubutton"><img src="images/closemenubutton.svg"></div>
                </div>
                <div class="nav">
                    <h3><?php echo translate(substr($parentCategory, strrpos($parentCategory, "/") + 1)); ?></h3>
                    <?php MyHTMLUtils::putToc($parentCategory, false, 2, false); ?>
                </div>
		<?php include("latest-articles.php"); ?>
            </div>
            <div style="clear:both;"></div>
        </div>
        <?php include("closed/php/footer.php"); ?>
    </body>
</html>
