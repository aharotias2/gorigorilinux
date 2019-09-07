#!/usr/bin/php-cgi
<?php
session_start();
$name = $_POST['name'];
$password = $_POST['password'];
if ($name == "osfadmin" && $password == "Platform_1987") {
    $_SESSION['role'] = 'admin';
?>
    <!DOCTYPE html>
    <html lang="ja">
	<head>
	    <meta charset="UTF-8">
	    <?php include("closed/php/css.php"); ?>
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <title>管理ページ</title>
	</head>
	<body>
	    <div class="contents">
		<div class="centerpane">
		    <h3>Open Source Fanboy 管理ページ</h3>
		    <h4>MENU</h4>
		    <p><a href="edit.php">テキストエディタを開く</a></p>
		    <p><a href="rsseditor.php">RSSフィード編集</a></p>
		    <p><a href="index.php">ホームに戻る</a></p>
		    </p>
		</div>
	</body>
    </html>
<?php } else { ?>
    <!DOCTYPE html>
    <html lang="ja">
	<head>
	    <meta http-equiv="refresh" content="0; URL='index.php'" />
	</head>
    </html>
<?php } ?>


