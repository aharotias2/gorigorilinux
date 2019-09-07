#!/usr/bin/php-cgi
<?php
/*
 * コメントを投稿する。
 */
require_once("closed/php/functions.php");
require_once("closed/php/CommentsDao.php");
$dao = new CommentsDao();
function get_param($param) {
    return filter_input(INPUT_POST, $param);
}
$entry = get_param("entry");
$articleName = get_param("article_name");
$userName = get_param("user_name");
$mailAddress = get_param("mail_address");
$comment = get_param("comment");
$anchor = get_param("anchor");
$dao->insert($articleName, $userName, $mailAddress, $comment, $anchor);
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta http-equiv="refresh" content="0; URL='article.php?entry=<?php echo $entry; ?>'" />
    </head>
</html>
