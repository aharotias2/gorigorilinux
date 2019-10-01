<?php
require_once("MyMarkdown.php");

class MyArticleUtils {
    public static function getEntryFromUrl($url) {
        return substr($url, strpos($url, '/entry-') + 7);
    }

    public static function getUrlFromEntry($entry) {
        return 'entry-' . $entry;
    }

    public static function getArticleTitle($pathName) {
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

    public static function getArticleText($pathName, $maxLength = 300) {
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
            $html = MyMarkdown::getMarkdown($pathName);
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

    public static function getArticleName($articleUrl) {
        $a = substr($articleUrl, strrpos($articleUrl, '/') + 1);
        $b = strpos($a, '_') + 1;
        $c = strrpos($a, '.');
        $a = substr($a, $b, $c - $b);
        return $a;
    }

    public static function getParentCategory($category) {
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

    public static function getPrevEntry($entry) {
        return getNthMatch('/[0-9]{3}_([a-z0-9-]+)\.(md|html)$/', self::getPrevEntryPath($entry), 1);
    }
    
    public static function getNextEntry($entry) {
        return getNthMatch('/[0-9]{3}_([a-z0-9-]+)\.(md|html)$/', self::getNextEntryPath($entry), 1);
    }

    public static function getNextEntryPath($entry) {
        $path = MyFileUtils::findArticlePath($entry);
        $fileName = substr($path, strrpos($path, "/") + 1);
        $fileNum = substr($fileName, 0, 3);
        $parentDir = substr($path, 0, strrpos($path, "/"));
        $tree = MyFileUtils::tree($parentDir);
        for ($i = 0; $i < count($tree) - 1; $i++) {
            if (substr($tree[$i][1], 0, 3) == $fileNum) {
                return $parentDir . "/" . $tree[$i + 1][1];
            }
        }
        $parentName = substr($parentDir, strrpos($parentDir, "/") + 1);
        $gparentDir = substr($parentDir, 0, strrpos($parentDir, "/"));
        $tree = MyFileUtils::tree($gparentDir);
        
        for ($i = 0; $i < count($tree) - 1; $i++) {
            if ($tree[$i][1] == $parentName) {
                return $gparentDir . "/" . $tree[$i + 1][1] . "/" . $tree[$i + 1][2][0][1];
            }
        }
        return null;
    }
    
    public static function getPrevEntryPath($entry) {
        $path = MyFileUtils::findArticlePath($entry);
        $fileName = substr($path, strrpos($path, "/") + 1);
        $fileNum = substr($fileName, 0, 3);
        $parentDir = substr($path, 0, strrpos($path, "/"));
        $tree = MyFileUtils::tree($parentDir);
        for ($i = 1; $i < count($tree); $i++) {
            if (substr($tree[$i][1], 0, 3) == $fileNum) {
                return $parentDir . "/" . $tree[$i - 1][1];
            }
        }
        $parentName = substr($parentDir, strrpos($parentDir, "/") + 1);
        $gparentDir = substr($parentDir, 0, strrpos($parentDir, "/"));
        $tree = MyFileUtils::tree($gparentDir);
        for ($i = 1; $i < count($tree); $i++) {
            if ($tree[$i][1] == $parentName) {
                $prevCategory = $tree[$i - 1];
                $count = count($prevCategory[2]);
                return $gparentDir . "/" . $prevCategory[1] . "/" . $prevCategory[2][$count - 1][1];
            }
        }
        return null;
    }
}
