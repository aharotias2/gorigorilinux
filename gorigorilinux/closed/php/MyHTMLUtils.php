<?php
require_once("functions-util.php");
class MyHTMLUtils {
    public static function putHeaderMenu($position) {
        $tree = MyFileUtils::tree("closed/articles");
        $bigCategories = [];
        $naviClass = $position == 1 ? "cp_navi_1" : "cp_navi_2";
        echo "<div class=\"cp_navi $naviClass\">";
        echo "<ul>";

        foreach ($tree as $bigCategory) {
            $bigCategoryName = $bigCategory[1];
            $bigCategoryChildren = $bigCategory[2];
            
            echo "<li>";
            if ($position == 1) {
                echo "<a href=\"toc.php?category=$bigCategoryName\">";
            } else {
                echo "<a>";
            }
            echo translate($bigCategoryName)."<span class=\"caret\"></span>";
            echo "</a>";
            echo "<div>";
            echo "<ul>";
            foreach ($bigCategoryChildren as $midCategory) {
                $midCategoryName = $midCategory[1];
                $midCategoryChildren = $midCategory[2];
                $midCategoryFirstArticle = $midCategoryChildren[0][2][0][1];
                $midCategoryFirstEntry = getNthMatch('/[0-9]+_(.*)\.(md|html)$/', $midCategoryFirstArticle, 1);
                echo "<li>";
                echo "<a href=\"" . MyArticleUtils::getUrlFromEntry($midCategoryFirstEntry) . "\">";
                echo translate($midCategoryName);
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

    public static function putArticleCategorySingle($category) {
	$catName = substr($category, strrpos($category, "/") + 1);
        $categoryColor = getCategoryColor($catName);
        $categoryNameJa = translate($catName);
        echo '<a href="toc.php?category='.$category.'">';
        echo '<span class="category_article_piece" style="background-color:'.$categoryColor.'">';
        echo $categoryNameJa;
        echo '</span>';
        echo '</a>';
    }
    
    public static function putArticleCategory($category) {
        echo '<div class="category_article">';
        $tmp = "";
        foreach (explode("/", $category) as $catName) {
	    $tmp .= $catName;
	    self::putArticleCategorySingle($tmp);
	    $tmp .= "/";
        }
        echo '</div>';
    }

    public static function putArticleTitle($pathName) {
        echo MyArticleUtils::getArticleTitle($pathName);
    }

    public static function putPrevLink($entry) {
        $prevEntry = MyArticleUtils::getPrevEntry($entry);
        if ($prevEntry != null) {
            echo "<div class=\"left_float max_width_30per\">\n";
            echo "<a href=\"" . MyArticleUtils::getUrlFromEntry($prevEntry) . "\">";
            echo "<img src=\"images/prev-page.svg\">";
            echo "</a>";
            echo "</div>\n";
        }
    }

    public static function putNextLink($entry) {
        $nextEntry = MyArticleUtils::getNextEntry($entry);
        if ($nextEntry != null) {
            echo "<div class=\"right_float max_width_30per\">\n";
            echo "<a href=\"" . MyArticleUtils::getUrlFromEntry($nextEntry) . "\">";
            echo "<img src=\"images/next-page.svg\">";
            echo "</a>";
            echo "</div>\n";
        }
    }

    private static function internalPutToc($category, $tree, $printDate, $maxDepth, $linkh4, $digFirstFile = false) {
        echo "<ul>\n";
        foreach ($tree as $item) {
            $type = $item[0];
            $name = $item[1];
            $children = $item[2];
            if ($type == "d") {
                $flg = preg_match("/[0-9]{2}-_/", $name);
                if (!$flg) {
                    echo '<li>';
                    echo '<h4 class="toc-category">';
                    if ($linkh4) {
                        if ($digFirstFile && count($children) > 0) {
                            $firstFile = getNthMatch("/[0-9]{3}_(.+)\.(md|html)$/", $children[0][1], 1);
                            echo '<a href="'. MyArticleUtils::getUrlFromEntry($firstFile);
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
                    self::internalPutToc($name, $children, $printDate, $maxDepth - 1, $linkh4, $digFirstFile);
                } else {
                    self::internalPutToc($category . "/" . $name, $children, $printDate, $maxDepth - 1, $linkh4, $digFirstFile);
                }
                if (!$flg) {
                    echo "</li>\n";
                }
            } else {
                $entry = getNthMatch('/^[0-9]{3}_(.*)\.(md|html)$/', $name, 1);
		if ($entry == null) {
                    return;
                }
                $fullPath = MyFileUtils::findArticlePath($entry);

                echo '<li>';
                echo '<a href="' . MyArticleUtils::getUrlFromEntry($entry) . '">';
                echo MyArticleUtils::getArticleTitle($fullPath);
                if ($printDate) {
                    echo " <span class=\"datetime\">" . MyFileUtils::getFilemtime($fullPath) . "</span>";
                }
                echo "</a>\n";
                echo "</li>\n";
            }
        }
        echo "</ul>\n";
    }
    
    public static function putToc($category, $printDate, $maxDepth, $linkh4, $digFirstFile = false) {
        $dirPath = MyFileUtils::getCategoryPath($category);
        $tree = MyFileUtils::tree($dirPath);
        self::internalPutToc($category, $tree, $printDate, $maxDepth, $linkh4, $digFirstFile);
    }

    public static function putPankuzu($category) {
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
}
