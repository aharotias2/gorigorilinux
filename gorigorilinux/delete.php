#!/usr/bin/php-cgi
<?php
/*
 * コメントを削除する。
 */
session_start();
require_once("closed/php/functions.php");
require("closed/php/CommentsDao.php");
$dao = new CommentsDao();
function get_param($param) {
    $value = filter_input(INPUT_POST, $param);
}
$entry = $_POST['entry'];
$articleName = $_POST['article_name'];
$anchor = $_POST['anchor'];
if ($_SESSION['role'] == 'admin') {
    $dao->delete($articleName, $anchor);
}
$articleUrl = MyArticleUtils::getUrlFromEntry($entry);
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta http-equiv="refresh" content="0; URL='<?=$articleUrl?>'" />
    </head>
</html>
