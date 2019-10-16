#!/usr/bin/php-cgi
<?php
/*
 * コメントを投稿する。
 */
require_once("closed/php/functions.php");
$dao = new CommentsDao();

function get_param($param) {
    if (isset($_GET[$param])) {
        return $_GET[$param];
    } else if (isset($_POST[$param])) {
        return $_POST[$param];
    } else {
        return null;
    }
}

$articleName = get_param("article_name");
$userName = get_param("user_name");
$mailAddress = get_param("mail_address");
$comment = get_param("comment");
$anchor = get_param("anchor");
$dao->insert($articleName, $userName, $mailAddress, $comment, $anchor);
$articleUrl = MyArticleUtils::getUrlFromEntry($articleName);
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta http-equiv="refresh" content="0; URL='<?=$articleUrl?>'" />
    </head>
    <body>
        <?=$articleName?><br>
        <?=$userName?><br>
        <?=$articleUrl?>
    </body>
</html>
