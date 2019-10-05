#!/usr/bin/php-cgi
<?php
session_start();
require_once("closed/php/functions.php");
$article = new ArticleInfo();
$sqlite = new SQLiteClient();
$article->id = $_GET['entry'];
$article->url = MyFileUtils::findArticlePath($article->id);
$article->category = substr(dirname($article->url), strlen("closed/articles/"));
$article->parent = dirname($article->category);
$article->title = MyArticleUtils::getArticleTitle($article->url);
$fullUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$now = new Datetime(date('Y/m/d H:i:s'));
if (!isset($_SESSION[$article->id]) || $now->sub(new DateInterval('PT24H')) > $_SESSION[$article->id]['time']) {
    $_SESSION[$article->id] = array('good' => 0, 'bad' => 0, 'time' => new Datetime(date('Y/m/d H:i:s')));
}

$dao = new CommentsDao(null);
if ($dao != null) {
    $commentsCount = $dao->getCommentsCount();
}

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="PICS-Label" content='(PICS-1.1 "http://www.classify.org/safesurf/" L gen true for "https://gorigorilinux.net" r (SS~~000 1 SS~~000 1))' />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="https://gorigorilinux.net/favicon.png" />
        <title><?php MyHTMLUtils::putArticleTitle($article->url); ?>: <?=$siteTitle?></title>
        <script type="text/javascript">
         articleName = "<?php=$article->id?>";
         session = {};
         session.good = <?=$_SESSION[$article->id]['good']?>;
         session.bad = <?=$_SESSION[$article->id]['bad']?>;
        </script>
        <?php include("closed/php/css.php"); ?>
    </head>
    <body>
        <?php include("closed/php/header.php"); ?>
        <div class="contents">
            <div class="leftpane">
                <div class="menu-header">
                    <div class="menubutton"><img src="images/closemenubutton.svg"></div>
                </div>
                <div class="nav">
                    <h3><?php echo translate(substr($article->parent, strrpos($article->parent, "/") + 1)); ?></h3>
                    <?php MyHTMLUtils::putToc($article->parent, false, 2, false); ?>
                </div>
		<?php include("latest-articles.php"); ?>
            </div>
            <div class="rightpane">
		<?php MyHTMLUtils::putHeaderMenu(2); ?>
                <div class="pagenavi">
                    <?php MyHTMLUtils::putPrevLink($article->id); MyHTMLUtils::putNextLink($article->id); ?>
                    <div style="clear:both;"></div>
                </div>
                <div class="article">
                    <div class="datetime">
                        <span class="mtime">
                            <img src="images/time.svg" alt="最終更新日時:"><?=MyFileUtils::getFilemtime($article->url)?>
                        </span>
                    </div>
                    <?php
                    $articleExtension = substr($article->url, strrpos($article->url, ".") + 1);
                    if ($articleExtension == "md") {
                        MyMarkdown::putMarkdown($article->url);
                    } else {
                        include($article->url);
                    }
                    ?>
                </div>
                <div class="pagenavi">
                    <?php MyHTMLUtils::putPrevLink($article->id); MyHTMLUtils::putNextLink($article->id); ?>
                    <div style="clear:both;"></div>
                </div>
                <div id="commentblock" class="comment_block">
                    <h4>評価・コメントをお残しください...</h4>
                    <div class="goodbad comment_switch">
                        <?php
                        $good = $_SESSION[$article->id]['good'];
                        $bad = $_SESSION[$article->id]['bad'];
                        $sqlite->putGoodbad($article->id, $good, $bad);
                        ?>
                        <button id="comment_switcher">コメントする</button>
                    </div>
                    <div class="post_block">
                        <form id="commentform" action="post.php" method="POST">
                            <p>コメント: <br><textarea rows="5" cols="80" id="comment_text" name="comment"></textarea></p>
                            <p>おなまえ: <br><input type="text" id="comment_user" name="user_name"></p>
                            <p>メール: <br><input type="text" id="comment_mail" name="mail_address"></p>
                            <p>
                                <input type="hidden" name="entry" value="<?=$article->id?>">
                                <input type="hidden" name="article_name" value="<?=$article->id?>">
                                <input type="hidden" name="anchor" value="0">
                                <button id="comment_submit" name="submit">コメントする</button>
                                <button id="comment_cancel" name="comment_cancel">キャンセル</button>
                            </p>
                        </form>
                    </div>
                    <?php $sqlite->putComments($article->id); ?>
                </div>
            </div>
            <div style="clear:both;"></div>
        </div>
        <?php include("closed/php/footer.php"); ?>
    </body>
</html>
