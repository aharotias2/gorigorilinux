#!/usr/bin/php
<?php
echo "Content-type: application/json; charset=utf-8\n\n";
include("closed/php/functions.php");
$logfile = fopen("log/api.log", "w");
if ($logfile == false) {
    return;
}
fwrite($logfile, "SCRIPT_NAME: {$_SERVER['SCRIPT_NAME']}\n");
fwrite($logfile, "DOCUMENT_ROOT: {$_SERVER['DOCUMENT_ROOT']}\n");
fwrite($logfile, "SERVER_URI: {$_SERVER['REQUEST_URI']}\n");
$query = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], '?') + 1);
$param = substr($query, 0, strpos($query, '='));
$cate = urldecode(substr($query, strpos($query, '=') + 1));
fwrite($logfile, "cate: $cate\n");
$dir = dirname($_SERVER['SCRIPT_NAME']);
$tmp = explode("/", $cate);
$cate = "";
$tmp2 = [];
foreach ($tmp as $word) {
    $tmp2[] = reverseTranslate($word);
}
$cate = implode("/", $tmp2);
fwrite($logfile, "cate: $cate\n");
$path = "$dir/closed/articles/" . $cate;
fwrite($logfile, "path: $path\n");
$articleName = substr($path, strrpos($path, "_") + 1);
$articleName = substr($articleName, 0, strrpos($articleName, "."));
if (strtolower($_SERVER['REQUEST_METHOD']) == "get") {
    if ($param == "cate") {
        $result = "";
        foreach(scandir($path) as $name) {
            if ($name[0] == '.') {
                continue;
            }
            if ($result != '') {
                $result .= ', ';
            }
            fwrite($logfile, "found a file: $name\n");
            $result .= "\"" . translate($name) . "\"";
        }
        fwrite($logfile, "[$result]\n");
        echo "[$result]";
    } else if ($param == "article") {
        $entry = dirname($cate) . "/" . substr(basename($cate), 0, 3);
        $itemUrl = "https://gorigorilinux.net/article.php?entry=$entry";
        $resultJson = "{\n";
        $resultJson .= "    \"itemLink\": \"$itemUrl\", \n";
        $resultJson .= "    \"itemAuthor\": \"info@singersongwriter.ciao.jp (田中喬之)\", \n";
        $resultJson .= "    \"itemCategory\": \"" . translate(substr($cate, 0, strpos($cate, '/'))) . "\", \n";
        $resultJson .= "    \"itemComment\": \"". $articleName ."\", \n";
        $resultJson .= "    \"itemGuid\": \"$itemUrl\", \n";
        $resultJson .= "    \"itemSource\": \"\", \n";
        $extension = substr($path, strrpos($path, "."));
        $article = fopen($path, "r");
        $line = fgets($article);
        fclose($article);
        if ($article !== false) {
            if ($extension == ".html" && preg_match('/<h3>(.*)<\/h3>/', $line, $matches) == 1) {
                $resultJson .= "    \"itemTitle\": \"" . htmlspecialchars($matches[1]) . "\", \n";
            } else if ($extension == ".md" && preg_match('/^# (.*)$/', $line, $matches) == 1) {
                $resultJson .= "    \"itemTitle\": \"" . $matches[1] . "\", \n";
            }
            $description = htmlspecialchars(ttGetArticleText($path, 1000));
            $description = str_replace("\\", "\\\\", $description);
            $resultJson .= "    \"itemDescription\": \"$description\", \n";
            $ftime = date('Y-n-j-H-i-s', filemtime($path));
            fwrite($logfile, "filemtime: $ftime\n");
            $resultJson .= "    \"itemPubDate\": \"$ftime\"\n";
        } else {
            $resultJson .= "    \"itemTitle\": \"Unkown\", \n";
            $resultJson .= "    \"itemDescription\": \"\"\n";
        }
        $resultJson .= "}\n";
        echo $resultJson;
        fwrite($logfile, "RESULT: $resultJson");
    }
}
fclose($logfile);
