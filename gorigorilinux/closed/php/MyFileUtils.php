<?php
class MyFileUtils {
    public static function tree($dir) {
        $list = [];
        foreach (scandir($dir) as $file) {
            if (substr($file, 0, 1) != '.') {
                array_push($list, $file);
            }
        }
        sort($list, SORT_STRING);
        $list2 = [];
        foreach ($list as $file) {
            if (is_dir("$dir/$file")) {
                array_push($list2, ["d", $file, self::tree("$dir/$file")]);
            } else {
                array_push($list2, ["f", $file]);
            }
        }
        return $list2;
    }
    
    public static function findArticlePath($articleName) {
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator("closed/articles"));
        $it->rewind();
        $articleName = str_replace('/', '\/', $articleName);
        while ($it->valid()) {
            if (!$it->isDot()) {
                $name = substr($it->key(), strrpos($it->key(), "/") + 1);
                $pattern = '/[0-9]+_' . $articleName . '\.(md|html)$/';
                if (preg_match($pattern, $it->key())) {
                    return $it->key();
                }
            }
            $it->next();
        }
        return null;
    }
    
    public static function getFilemtime($file) {
        $t = filemtime($file);
        //return ttConvertToJdate(date("Y-m-d H:i:s", $t));
        return date("Y.m.d H:i:s", $t);
    }

    public static function getCategoryPath($category) {
        if ($category == "") {
            return "closed/articles";
        } else {
            return "closed/articles/" . $category;
        }
    }
}
