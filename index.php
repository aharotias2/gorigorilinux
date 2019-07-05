#!/usr/bin/php-cgi
<?php
session_start();
$localPrefix = "closed/php/";
require($localPrefix . "functions_comments.php");
require_once($localPrefix . "functions_markdown.php");
$sqlite = new SQLiteClient();
include($localPrefix . "functions.php");
$page = isset($_GET['page']) ? $_GET['page'] : "";
if ($page == "") {
    $page = 1;
}
$bigCategory = isset($_GET['category']) ? $_GET['category'] : "";
$order = isset($_GET['order']) ? $_GET['order'] : "";
if ($order == "" || ($order != "desc" && $order != "asc")) {
    $order = "desc";
}
if ($order == "desc") {
    $reverseOrder = "asc";
} else {
    $reverseOrder = "desc";
}
$isConstPage = false;
switch ($bigCategory) {
    case "by_category":
    case "todo":
    case "links":
	$isConstPage = true;
	break;
}
$array = array("Linux", "アプリケーション", "プログラミング", "ITニュース", "雑文");
$bigMenu = "";
foreach ($array as $category) {
    if (reverseTranslate($category) == $bigCategory) {
        $bigMenu .= "<li class=\"current_category\">";
    } else {
        $bigMenu .= "<li>";
    }
    $categoryEn = reverseTranslate($category);
    $bigMenu .= "<a href=\"index.php?category=$categoryEn&page=1&order=$order\">$category</a></li>";
}
?>
<!DOCTYPE html>
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
            <div class="centerpane">
                <div class="index_category_menu">
                    <ul class="mainmenu">
                        <li<?php echo ($bigCategory == "")?' class="current_category"':''; ?>>
                            <a href="index.php?category=&page=1">全て</a>
			</li>
                        <?php echo $bigMenu; ?>
                        <li<?php echo ($bigCategory == "by_category")?' class="current_category"':''; ?>>
                            <a href="index.php?category=by_category&page=1&order=desc">カテゴリ別</a>
                        </li>       
                        <li<?php echo ($bigCategory == "todo")?' class="current_category"':''; ?>>
			    <a href="index.php?category=todo&page=1&order=desc">TODO</a>
                        </li>
                        <li<?php echo ($bigCategory == "self")?' class="current_category"':''; ?>>
			    <a href="article.php?entry=misc/others/01_self/001">このサイトについて</a>
                        </li>
			<li<?php echo ($bigCategory == "links")?' class="current_category"':''; ?>>
                            <a href="index.php?category=links&page=1&order=desc">リンク</a>
			</li>
                    </ul>
                </div>
                <div class="index_latest_articles">
		    <?php if ($isConstPage === false) { ?>
			<div class="to_toc">
			    <a href="toc.php?category=<?php echo $bigCategory ?>">記事一覧</a>
			</div>
			<div class="select_order" style="float:right">
			    <select name="select_order">
                                <option value="desc" <?php if ($order == "desc") echo "selected"; ?>>記事が新しい順</option>
                                <option value="asc" <?php if ($order == "asc") echo "selected"; ?>>記事が古い順</option>
			    </select>
			</div>
		    <?php } ?>
                    <h3 class="toppageh3">
                        <?php if ($bigCategory == "by_category") { ?>
                            カテゴリ一覧
                        <?php } else if ($bigCategory == "links") { ?>
                            リンク集
                        <?php } else if ($bigCategory == "todo") { ?>
                            TODOリスト
                        <?php } else  { ?>
                            <?php if ($order == "desc") { ?>
                                最近の記事
                            <?php } else { ?>
                                古い記事
                            <?php } ?>
                        <?php } ?>
                    </h3>
                    <div style="clear:both"></div>
                    <?php
                    if ($bigCategory == "by_category") {
                        echo "<div class=\"toppagetoc\">";
                        ttPutToc("", true, 2, true, true);
                        echo "</div>";
                    } else if ($bigCategory == "todo") {
                        echo "<div class=\"article\">";
                        include("closed/todo.html");
                        echo "</div>";
                    } else if ($bigCategory == "links") {
                        echo "<div class=\"article\">";
                        include("closed/links.html");
                        echo "</div>";
		    } else {
			$filecount = ttPutLatestArticles($bigCategory, $order, $page, 10);
			if ($filecount == 0) {
			    echo "<div class=\"no_entries\">記事はまだありません</div>";
			}
                    }
                    ?>
                </div>
                <div style="clear:both"></div>
            </div>
        </div>
        <?php include($localPrefix . "footer.php"); ?>
    </body>
</html>

