<?php
require_once("settings.php");
require_once("CommentsDao.php");

function translate($key) {
    global $translations;
    for ($i = 0; $i < count($translations); $i++) {
        if ($translations[$i][0] == $key) {
            return $translations[$i][1];
        }
    }
    return $key;
}

function reverseTranslate($key) {
    global $translations;
    for ($i = 0; $i < count($translations); $i++) {
        if ($translations[$i][1] == $key) {
            return $translations[$i][0];
        }
    }
    return $key;
}

function getCategoryColor($cateName) {
    global $translations;
    for ($i = 0; $i < count($translations); $i++) {
        if ($translations[$i][0] == $cateName) {
            return $translations[$i][2];
        }
    }
}

function ttGetDate($intDate) {
    return date("Y年 n月 j日", $intDate);
}

function ttGetQuery($key) {
    $value = filter_input(INPUT_POST, $key);
    if ($value) {
        return htmlspecialchars($value);
    } else {
        return "";
    }
}

function ttGetCategoryPath($category) {
    if ($category == "") {
        return "closed/articles";
    } else {
        return "closed/articles/" . $category;
    }
}

function ttGetParentCategory($category) {
    if ($category == "") {
        return "";
    }
    $names = explode("/", $category);
    if (sizeof($names) >= 2) {
        $result = $names[0];
        for ($i = 1; $i < sizeof($names) - 1; $i++) {
            $result .= "/" . $names[$i];
        }
        return $result;
    }
}

function ttPutHeaderMenu($position) {
    $bigCategories = [];
    $naviClass = $position == 1 ? "cp_navi_1" : "cp_navi_2";
    echo "<div class=\"cp_navi $naviClass\">";
    echo "<ul>";
    foreach (scandir("closed/articles") as $bigCategory) {
        if ($bigCategory[0] == ".") {
            continue;
        }
        $bigCategories[] = $bigCategory;
    }

    foreach ($bigCategories as $bigCategory) {
        $midCategories = [];
        foreach (scandir("closed/articles/$bigCategory") as $midCategory) {
            if ($midCategory[0] == ".") {
                continue;
            }
            $midCategories[] = $midCategory;
        }

        echo "<li>";
        if ($position == 1) {
            echo "<a href=\"toc.php?category=$bigCategory\">";
        } else {
            echo "<a>";
        }
        echo translate($bigCategory)."<span class=\"caret\"></span>";
        echo "</a>";
        echo "<div>";
        echo "<ul>";
        foreach ($midCategories as $midCategory) {
            $smallCategory = "";
            foreach (scandir("closed/articles/$bigCategory/$midCategory") as $tmp) {
                if ($tmp[0] != ".") {
                    if ($smallCategory == "" || strcmp($smallCategory, $tmp) > 0) {
                        $smallCategory = $tmp;
                    }
                }
            }
            echo "<li>";
            echo "<a href=\"article.php?entry=$bigCategory/$midCategory/$smallCategory/001\">";
            echo translate($midCategory);
            echo "</a>";
            echo "</li>";
        }
        echo "</ul>";
        echo "</div>";
        echo "</li>";
    }
    echo "</ul>";
    echo "</div>";
}

function ttPutPankuzu($category) {
    $names = explode("/", $category);
    print(" <a href=\"toc.php?category=\">TOP</a> ");
    if ($category == "") {
        return;
    }
    for ($i = 0; $i < sizeof($names); $i++) {
        if (preg_match("/[0-9]{2}-_/", $names[$i])) {
            continue;
        }
        print("<img src=\"images/breadcrumbdelim3.svg\">");
        print("<a href=\"toc.php?category=");
        for ($j = 0; $j <= $i; $j++) {
            if ($names[$j] == "") {
                continue;
            }
            print($names[$j]);
            if ($j < $i) {
                print("/");
            }
        }
        if (preg_match("/[0-9]{2}_/", $names[$i])) {
            print("\">" . translate($names[$i]) . "</a>");
        } else {
            print("\">" . translate($names[$i]) . "</a>");
        }
    }
}

function ttGetArticleTitle($pathName) {
    $file = fopen($pathName, "r") or die("unable to open file $pathName");
    $h3 = fgets($file);
    fclose($file);
    $extension = substr($pathName, strrpos($pathName, "."));
    if ($extension == ".html") {
        return str_replace("</h3>", "", str_replace("<h3>", "", $h3));
    } else if ($extension == ".md") {
        return substr($h3, 2);
    } else {
        return $h3;
    }
}

function ttGetArticleText($pathName, $maxLength = 300) {
    $log = fopen("/home/ta/Public/log/tmp.log", "w");
    $extension = substr($pathName, strrpos($pathName, "."));
    $lines = array();
    $text = "";
    if ($extension == ".html") {
        $file = fopen($pathName, "r") or die("unable to open file $pathName");
        fgets($file);
        while (false !== ($line = fgets($file))) {
            $lines[] = $line;
        }
        fclose($file);
    } else if ($extension == ".md") {
        require_once("functions_markdown.php");
        $html = ttGetMarkdown($pathName);
        $lines = explode("\n", $html);
        array_shift($lines);
    } else {
        return "";
    }

    foreach ($lines as $line2) {
        fwrite($log, $line2);
        $line2 = preg_replace('/<(\'.*?\'|".*?"|[^\'">])*?>/', '', $line2);
        $line2 = preg_replace("/[ \n\r\t]+/", " ", $line2);
        if (strlen($text) + strlen($line2) <= $maxLength) {
            $text .= $line2;
        } else {
            $currentLength = $maxLength - strlen($text);
            while (ord($line2[$currentLength - 1]) >> 6 == 0b10) {
                $currentLength--;
            }
            $text .= substr($line2, 0, $currentLength - 1) . "...";
            break;
        }
    }
    fclose($log);
    return $text;
}

function ttPutArticleTitle($pathName) {
    echo ttGetArticleTitle($pathName);
}

function ttGetArticleName($articleUrl) {
    $a = substr($articleUrl, strrpos($articleUrl, '/') + 1);
    $b = strpos($a, '_') + 1;
    $c = strrpos($a, '.');
    $a = substr($a, $b, $c - $b);
    return $a;
}

function ttGetEntryPath($entry) {
    $category = dirname("closed/articles/" . $entry);
    $number = substr($entry, strrpos($entry, "/") + 1, 3);
    foreach (scandir($category) as $name) {
        if ($name == "." || $name == "..") {
            continue;
        }
        if (strpos($name, $number) === 0) {
            return $category . "/" . $name;
        }
    }
    http_response_code(404);
    die();
}

function ttPutToc($category, $printDate, $maxDepth, $linkh4, $digFirstFile = false) {
    $dirPath = ttGetCategoryPath($category);
    echo "<ul>\n";
    $log = fopen("/home/ta/Public/log/app.log", "a");
    foreach (scandir($dirPath) as $name) {
        if (preg_match("/^\.\.?/", $name)) {
            continue;
        }
        $fullPath = $dirPath . "/" . $name;
        if ($maxDepth != 0) {
            if (is_dir($fullPath)) {
                $flg = preg_match("/[0-9]{2}-_/", $name);
                if (!$flg) {
                    echo '<li>';
                    echo '<h4 class="toc-category">';
                    if ($linkh4) {
                        fwrite($log, "entry: $category/$name\n");
                        if ($digFirstFile) {
                            echo '<a href="article.php?entry=';
                            echo ttGetFirstFile($category . "/" . $name);
                        } else {
                            echo '<a href="toc.php?category=';
                            echo ($category === "" ? "" : $category . "/") . $name;
                        }
                        echo '">';
                    }
                    echo translate($name);
                    if ($linkh4) {
                        echo '</a>';
                    }
                    echo "</h4>\n";
                }
                if ($category == "") {
                    ttPutToc($name, $printDate, $maxDepth - 1, $linkh4, $digFirstFile);
                } else {
                    ttPutToc($category . "/" . $name, $printDate, $maxDepth - 1, $linkh4, $digFirstFile);
                }
                if (!$flg) {
                    echo "</li>\n";
                }
            } else {
                if (!preg_match("/^[0-9]{3}/", $name) || preg_match("/~$/", $name)) {
                    continue;
                }
                echo '<li>';
                echo '<a href="article.php?entry=' . $category . '/' . substr($name, 0, 3) . '">';
                echo ttGetArticleTitle($fullPath);
                if ($printDate) {
                    echo " <span class=\"datetime\">" . ttGetFilemtime($fullPath) . "</span>";
                }
                echo "</a>\n";
                echo "</li>\n";
            }
        }
    }
    fclose($log);
    echo "</ul>\n";
}

function ttPutLatestArticles($bigCategory, $order, $page, $maxNumberOfContents, $dns = null) {
    $dao = new CommentsDao($dns);
    if ($dao != null) {
        $commentsCount = $dao->getCommentsCount();
    }
    if ($bigCategory != "") {
        $prefix = "closed/articles/$bigCategory";
    } else {
        $prefix = "closed/articles";
    }
    $list = ttGetFileList($prefix, $order);
    $numberOfArticles = sizeof($list);
    if ($numberOfArticles == 0) {
        return 0;
    }
    $list = array_slice($list, ($page - 1) * $maxNumberOfContents, $maxNumberOfContents);
    foreach ($list as $filePath) {
        $entry = str_replace($prefix . "/", "", $filePath);
        $n = strrpos($entry, "/");
        $category = substr($entry, 0, $n);
        $fileName = substr($entry, $n + 1, 3);
        $articleId = $category . "/" . $fileName;
        $articleName = ttGetArticleName($filePath);
        $articleText = ttGetArticleText($filePath);
        echo "<div class=\"latest_article\">";
        if ($bigCategory != "") {
            echo "<h5><a href=\"article.php?entry=$bigCategory/$articleId\">";
        } else {
            echo "<h5><a href=\"article.php?entry=$articleId\">";
        }
        echo ttGetArticleTitle($filePath);
        echo "<span class=\"date_article\">(" . ttGetFilemtime($filePath) . ")</span>";
        echo "</a></h5>";
        if ($bigCategory != "") {
            echo "<p>$articleText<a href=\"article.php?entry=$bigCategory/$articleId\">(続きを読む)</a></p>";
        } else {
            echo "<p>$articleText<a href=\"article.php?entry=$articleId\">(続きを読む)</a></p>";
        }
        echo "<div class=\"category_article\">";
        $tmp = "";
        foreach (explode("/", $category) as $catName) {
            $tmp = $tmp . $catName;
            if ($bigCategory == "") {
                echo "<a href=\"toc.php?category=$tmp\">";
            } else {
                echo "<a href=\"toc.php?category=$bigCategory/$tmp\">";
            }
            echo "<span class=\"category_article_piece\" style=\"background-color:";
            $displayName = translate($catName);
            echo     getCategoryColor($catName);
            echo "\">";
            echo $displayName;
            echo "</span>";
            echo "</a>";
            $tmp .= "/";
        }
        echo "</div>";
        echo "<span class=\"comments_count\">コメント: ";
        if (array_key_exists($articleName, $commentsCount)) {
            echo $commentsCount[$articleName];
        } else {
            echo "0";
        }
        echo "</span>";
        echo "</div>";
    }

    $maxPageNumber = floor($numberOfArticles / $maxNumberOfContents) + 1;
    $prev = $page > 1 ? $page - 1 : 0;
    $next = $page <= $maxPageNumber ? $page + 1 : 0;
    $maxButtonNumber = 6;

    // 最新記事が1ページ以上ある場合はページネーションを表示する。
    if ($maxPageNumber > 1) {
        echo "<div class=\"pagination\"><ul>";
        echo "<li class=\"leftend\">";
        echo ($prev != 0 ? "<a href=\"index.php?category=$bigCategory&page=$prev&order=$order\">" : "") . "&lt;" . ($prev != 0 ? "</a>" : "");
        echo "</li>";
        $i = 1;
        if ($page == "" || $page == $i) {
            echo "<li class=\"current_page\">$i</li>";
        } else {
            echo "<li><a href=\"index.php?category=$bigCategory&page=1&order=$order\">$i</a></li>";
        }
        if ($page > $maxButtonNumber - 2) {
            echo "<li>…</li>";
        }
        $i = $page - floor($maxButtonNumber / 2);
        if ($i < 1) {
            $i = 1;
        }
        for ($j = 1; $j < $maxButtonNumber && $i < $maxPageNumber - 1; $j++) {
            $i++;
            if ($page == "" || $page == $i) {
                echo "<li class=\"current_page\">$i</li>";
            } else {
                echo "<li><a href=\"index.php?category=$bigCategory&page=$i&order=$order\">$i</a></li>";
            }
        }
        if ($page < $maxPageNumber - floor($maxButtonNumber / 2)) {
            echo "<li>…</li>";
        }
        if ($page == "" || $page == $maxPageNumber) {
            echo "<li class=\"current_page\">$page</li>";
        } else {
            echo "<li><a href=\"index.php?category=$bigCategory&page=$maxPageNumber&order=$order\">$maxPageNumber</a></li>";
        }
        echo "<li class=\"rightend\">";
        echo ($next != 0 ? "<a href=\"index.php?category=$bigCategory&page=$next&order=$order\">" : "") . "&gt;" . ($next != 0 ? "</a>" : "");
        echo "</li>";
        echo "</ul>";
        echo "</div>";
    }
    return 1;
}

function ttPutLatestArticlesLite($page, $maxNumberOfContents, $dns = null) {
    global $categoryColor;
    $dao = new CommentsDao($dns);
    if ($dao != null) {
        $commentsCount = $dao->getCommentsCount();
    }
    $prefix = "closed/articles";
    $list = ttGetFileList($prefix, "desc");
    $numberOfArticles = sizeof($list);
    $list = array_slice($list, ($page - 1) * $maxNumberOfContents, $maxNumberOfContents);
    foreach ($list as $filePath) {
        $entry = str_replace($prefix . "/", "", $filePath);
        $n = strrpos($entry, "/");
        $category = substr($entry, 0, $n);
        $fileName = substr($entry, $n + 1, 3);
        $articleId = $category . "/" . $fileName;
        $articleName = ttGetArticleName($filePath);
        echo "<div class=\"latest_article_lite\">";
        echo "<h4><a href=\"article.php?entry=" . $articleId . "\">";
        echo ttGetArticleTitle($filePath);
        echo "<span class=\"date_article\">(" . ttGetFilemtime($filePath) . ")</span>";
        echo "</a>";
        echo "<span class=\"comments_count\">コメント: ";
        if (array_key_exists($articleName, $commentsCount)) {
            echo $commentsCount[$articleName];
        } else {
            echo "0";
        }
        echo "</span>";
        echo "</h4>";
        echo "<div class=\"category_article\">";
        $tmp = "";
        foreach (explode("/", $category) as $catName) {
            $tmp = $tmp . $catName;
            if (preg_match("/[0-9]{2}-_/", $catName)) {
                continue;
            }
            $displayname = translate($catName);
            echo "<a href=\"toc.php?category=$tmp\">";
            echo "<span class=\"category_article_piece\" style=\"background-color:";
            echo getCategoryColor($catName);
            echo "\">";
            echo $displayname;
            echo "</span>";
            echo "</a>";
            $tmp .= "/";
        }
        echo "</div>";
        echo "</div>";
    }
}

function ttGetFileList($dir, $order) {
    $res = [];
    foreach (scandir($dir) as $name) {
        if ($name[0] === '.') {
            continue;
        }
        $filePath = $dir . "/" . $name;
        if (is_dir($filePath)) {
            $children = ttGetFileList($filePath, $order);
            ttMergeOrderByMdate($res, $children, $order);
        } else if (is_file($filePath)) {
            $extension = substr($filePath, strrpos($filePath, "."));
            if ($extension == ".html" || $extension == ".md") {
                ttFileListInsertOrderByMdate($res, $filePath, $order);
            }
        }
    }
    return $res;
}

function ttMergeOrderByMdate(&$arraya, $arrayb, $order) {
    foreach ($arrayb as $e) {
        ttFileListInsertOrderByMdate($arraya, $e, $order);
    }
}

function ttFileListInsertOrderByMdate(&$arrayc, $e2, $order) {
    switch (sizeof($arrayc)) {
    case 0:
        $arrayc[] = $e2;
        break;
    case 1:
        if ($order == "desc" && filemtime($e2) >= filemtime($arrayc[0])) {
            array_splice($arrayc, 0, 0, $e2);
        } else {
            $arrayc[] = $e2;
        }
        break;
    default:
        for ($i = 0; $i < sizeof($arrayc); $i++) {
            if ($order == "desc") {
                if (filemtime($e2) >= filemtime($arrayc[$i])) {
                    array_splice($arrayc, $i, 0, $e2);
                    return;
                }
            } else {
                if (filemtime($e2) < filemtime($arrayc[$i])) {
                    array_splice($arrayc, $i, 0, $e2);
                    return;
                }
            }
        }
        $arrayc[] = $e2;
        break;
    }
}

function ttPutPrevLink() {
    $entry = $_GET['entry'];
    $number = substr(substr($entry, strrpos($entry, "/") + 1), 0, 3) - 1;
    $zeroNumber = sprintf("%03d", $number);
    $category = substr($entry, 0, strrpos($entry, "/"));
    $dir = "closed/articles/" . $category;
    if ($zeroNumber != "000") {
        foreach (scandir($dir) as $filename) {
            $filenumber = substr($filename, 0, 3);
            if ($zeroNumber == $filenumber) {
                $title = ttGetArticleTitle("$dir/$filename");
                echo "<div class=\"left_float max_width_30per\">\n";
                echo "<a href=\"article.php?entry=$category/$zeroNumber\">";
                echo "<img src=\"images/prev-page.svg\">";
                echo "</a>";
                echo "</div>\n";
                return;
            }
        }
    } else {
        $tmp = substr($entry, 0, strrpos($entry, "/"));
        $tmp2 = substr($tmp, strrpos($tmp, "/") + 1);
        $tmp3 = substr($tmp, 0, strrpos($tmp, "/"));
        $cateNumber = substr($tmp2, 0, 2) - 1;
        $category = $tmp3;
        $cateNumber = sprintf("%02d", $cateNumber);
        $dir = "closed/articles/" . $category;
        foreach (scandir($dir) as $dirname) {
            $dirnumber = substr($dirname, 0, 2);
            if ($cateNumber == $dirnumber) {
                $dir2 = "$dir/$dirname";
                $maxNumber = 0;
                $filename2 = "";
                foreach (scandir($dir2) as $filename) {
                    $filenumber = substr($filename, 0, 3);
                    $filenumber = sprintf("%d", $filenumber);
                    if ($maxNumber < $filenumber) {
                        $maxNumber = $filenumber;
                        $filename2 = $filename;
                    }
                }
                $title = ttGetArticleTitle("$dir2/$filename2");
                $zeroNumber = sprintf("%03d", $maxNumber);
                echo "<div class=\"left_float max_width_30per\">\n";
                echo "<a href=\"article.php?entry=$tmp3/$dirname/$zeroNumber\">";
                echo "<img src=\"images/prev-page.svg\">";
                echo "</a>";
                echo "</div>\n";
                return;
            }
        }
    }
}

function ttPutNextLink() {
    $entry = $_GET['entry'];
    //$number = sprintf("%03d", substr($entry, strrpos($entry, "/") + 1) + 1);
    $number = substr(substr($entry, strrpos($entry, "/") + 1), 0, 3) + 1;
    $zeroNumber = sprintf("%03d", $number);
    $category = substr($entry, 0, strrpos($entry, "/"));
    $dir = "closed/articles/" . $category;
    foreach (scandir($dir) as $filename) {
        $filenumber = substr($filename, 0, 3);
        if ($zeroNumber == $filenumber) {
            $title = ttGetArticleTitle("$dir/$filename");
            echo "<div class=\"right_float max_width_30per\">\n";
            echo "<a href=\"article.php?entry=$category/$zeroNumber\">";
            echo "<img src=\"images/next-page.svg\">";
            echo "</a>";
            echo "</div>\n";
            return;
        }
    }
    $tmp = substr($entry, 0, strrpos($entry, "/"));
    $tmp2 = substr($tmp, 0, strrpos($tmp, "/"));
    $tmp3 = substr($tmp, strrpos($tmp, "/") + 1);
    $cateNumber = substr($tmp3, 0, 2);
    $nextCateNumber = sprintf("%d", $cateNumber) + 1;
    $dirname = "closed/articles/$tmp2";
    foreach (scandir($dirname) as $subdirname) {
        $subNumber = substr($subdirname, 0, 2);
        if ($nextCateNumber == $subNumber) {
            foreach (scandir("$dirname/$subdirname") as $filename) {
                if (substr($filename, 0, 3) == "001") {
                    $title = ttGetArticleTitle("$dirname/$subdirname/$filename");
                    echo "<div class=\"right_float max_width_30per\">\n";
                    echo "<a href=\"article.php?entry=$tmp2/$subdirname/001\">";
                    echo "<img src=\"images/next-page.svg\">";
                    echo "</a>";
                    echo "</div>\n";
                    return;
                }
            }
        }
    }
}

function ttGetFirstFile($dir) {
    $path = "closed/articles/" . $dir;
    $f = "";
    foreach (scandir($path) as $file) {
        $f = $file;
        if (substr($f, 0, 3) == "01_") {
            break;
        }
    }
    if ($f != "") {
        $path = $path . "/" . $f;
        $dir = $dir . "/" . $f;
        $files = scandir($path);
        foreach (scandir($path) as $file) {
            $f = $file;
            if (substr($f, 0, 4) == "001_") {
                return $dir . "/" . $f;
            }
        }
    }
    return "";
}

function ttConvertNumberToKanji ($number) {
    $kanji = array("零", "一", "二", "三", "四", "五", "六", "七", "八", "九");
    $result = $kanji[$number % 10];
    if ($number > 10) {
        $tmp = floor(($number % 100) / 10);
        if ($tmp == 1) {
            $result = "十" . $result;
        } else if ($tmp == 2) {
            $result = "廿" . $result;
        } else if ($tmp == 3) {
            $result = "卅" . $result;
        } else {
            $result = $kanji[$tmp] . "十" . $result;
        }
    }
    if ($number > 100) {
        $tmp = floor(($number % 1000) / 100);
        if ($tmp == 1) {
            $result = "百" . $result;
        } else {
            $result = $kanji[$tmp] . "百" . $result;
        }
    }
    if ($number > 1000) {
        $tmp = floor(($number % 10000) / 1000);
        if ($tmp == 1) {
            $result = "千" . $result;
        } else {
            $result = $kanji[$tmp] . "千" . $result;
        }
    }
    return $result;
}    

function ttGetGengo($year, $month) {
    if ($year < 2019 || ($year == 2019 && $month <= 4)) {
        return array("gengo" => "平成", "year" => $year - 1988);
    } else {
        if ($year == 2019) {
            return array("gengo" => "令和", "year" => "元");
        } else {
            return array("gengo" => "令和", "year" => $year - 2018);
        }
    }
}


function ttConvertToJdate($datetime) {
    // $datetime format: YYYY-MM-DD HH:mm:ss
    $y = substr($datetime, 0, 4);
    $m = substr($datetime, 5, 2);
    $d = substr($datetime, 8, 2);
    $h = substr($datetime, 11, 2);
    $i = substr($datetime, 14, 2);
    $m = $m{0} == '0' ? $m{1} : $m;
    $d = $d{0} == '0' ? $d{1} : $d;
    $h = $h{0} == '0' ? $h{1} : $h;
    $i = $i{0} == '0' ? $i{1} : $i;
    $wy = "";
    $date = "";
    $time = "";
    $gengo = ttGetGengo($y, $m);
    if ($gengo["year"] == 1) {
        $wy = $gengo["gengo"] . "元年";
    } else {
        $wy = $gengo["gengo"] . $gengo["year"] . "年";
    }
    if ($m == 1 && $d == 1) {
        $date = "元旦";
    } else if ($m == 12 && $d == 31) {
        $date = "大晦日";
    } else {
        $date = "${m}月${d}日";
    }
    if (($h == 12 && $i < 20) || ($h == 11 && $i > 40)) {
        $time = "正午ごろ";
    } else {
        if ($h < 12) {
            $A = "午前";
        } else {
            $A = "午後";
            $h -= 12;
        }
        $time = "${A}${h}時${i}分";
    }
    return $wy . $date . $time;
}

function ttGetFilemtime($file) {
    $t = filemtime($file);
    //return ttConvertToJdate(date("Y-m-d H:i:s", $t));
    return date("Y.m.d H:i:s", $t);
}

function ttFindPath($entry) {
    $dirs = explode("/", $entry);
    if ($dirs[0] == "closed" && $dirs[1] == "articles" && count($dirs) == 6) {
        $n = array_pop($dirs);
        foreach (scandir(implode("/", $dirs)) as $fileName) {
            if ($fileName !== "." && $fileName !== ".." && substr($fileName, 0, 3) === $n) {
                return implode("/", $dirs) . "/" . $fileName;
            }
        }
        return "";
    } else {
        return $entry;
    }
}
