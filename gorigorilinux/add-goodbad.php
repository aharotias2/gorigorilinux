<?php
session_start();
echo "Content-Type: application/json; charset=utf-8\n\n";
include("closed/php/functions.php");
include("closed/php/functions_comments.php");
$sqlite = new SQLiteClient();
$log = fopen("log/app.log", "a");
if (isset($_POST['article_name'])) {
    $articleName = $_POST['article_name'];
}
if (isset($_POST['good'])) {
    $good = $_POST['good'];
}
if (isset($_POST['bad'])) {
    $bad = $_POST['bad'];
}
fwrite($log, "PARAMS: " . implode(", ", $_POST) . "\n");
if ($articleName != null && $good != null && $bad != null) {
    $sqlite->addGoodbad($articleName, $good, $bad);
    $_SESSION[$articleName]['good'] = $good > 0 ? 1 : 0;
    $_SESSION[$articleName]['bad'] = $bad > 0 ? 1 : 0;
}
fclose($log);
echo "{}";

