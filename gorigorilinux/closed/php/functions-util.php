<?php
require_once("settings.php");

function startsWith($string, $startString) {
    return strpos($string, $startString) == 0;
}

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
