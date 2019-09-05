<?php
$siteTitle = "ごりごりりぬっくす.net";
date_default_timezone_set("Asia/Tokyo");
// 色の設定
$colors = [
    "prog" => "darkcyan",

    "c" => "darkslategray", //slate green
    "cLangColor" => "darkgreen",

    "java" => "forestgreen",

    "general" => "seagreen",
    "generalColors" => "royalblue",

    "apps" => "navy",

    "linuxApp" => "mediumblue",
    "linuxAppsColor" => "darkorange",

    "itnews" => "seagreen",

    "2019" => "chocolate",
    "itNewsMonthColor" => "darkorange",

    "misc" => "#EF857D",

    "self" => "darkolivegreen",
    "othersColor" => "mediumvioletred",

    "diary" => "sienna",
    "diaryColor" => "darkmagenta",

    "news" => "sienna",
    "normalNewsColor" => "olive",

    "docs" => "darkgreen",
    "readmeColor" => "midnightblue",

    "linux" => "darkolivegreen",

    "commands" => "teal",
    "linuxCommandsColor" => "olive",

    "slackware" => "dimgray",
    "slackwareColor" => "royalblue",

    "beginners" => "deeppink",
    "beginnersColor" => "red",
];

$translations = [
    // プログラミング
    ["prog", "プログラミング", $colors["prog"]],

    ["c", "C言語", $colors["c"]],
    ["01_prolog", "準備", $colors["cLangColor"]],

    ["java", "Java", $colors["java"]],

    ["general", "プログラミング総合", $colors["general"]],
    ["01_basic", "超基本", $colors["generalColors"]],
    ["02_oop", "オブジェクト指向", $colors["generalColors"]],
    
    // アプリケーション
    ["apps", "アプリケーション", $colors["apps"]],

    ["linux", "Linux", $colors["linuxApp"]],
    ["01_system", "システム系", $colors["linuxAppsColor"]],
    ["02_texteditors", "テキストエディタ", $colors["linuxAppsColor"]],
    ["03_multimedia", "マルチメディア", $colors["linuxAppsColor"]],

    // ITニュース
    ["itnews", "ITニュース", $colors["itnews"]],

    ["2019", "2019年", $colors["2019"]],
    ["03_march", "3月", $colors["itNewsMonthColor"]],
    ["04_april", "4月", $colors["itNewsMonthColor"]],
    ["05_may", "5月", $colors["itNewsMonthColor"]],
    ["06_june", "6月", $colors["itNewsMonthColor"]],

    // 雑文
    ["misc", "雑文", $colors["misc"]],

    ["self", "私事", $colors["self"]],
    ["01_self", "自己紹介", $colors["othersColor"]],
    ["02_gadgets", "持ち物", $colors["othersColor"]],
    ["03_old-stories", "思い出話", $colors["othersColor"]],
    ["04_life", "生活", $colors["othersColor"]],
    ["05_social", "社会", $colors["othersColor"]],
    ["06_adult", "アダルト", "black"],

    ["diary", "日記", $colors["diary"]],
    ["01_201905", "2019年5月", $colors["diaryColor"]],
    ["02_201907", "2019年7月", $colors["diaryColor"]],
    ["03_201908", "2019年8月", $colors["diaryColor"]],

    ["news", "ニュース (IT、Linux関係ない)", $colors["news"]],
    ["01_201906", "2019年6月", $colors["normalNewsColor"]],

    ["docs", "ドキュメント", $colors["docs"]],
    ["01_readmes", "README", $colors["readmeColor"]],

    // Linux
    ["linux", "Linux", $colors["linux"]],

    ["commands", "Linuxコマンド", $colors["commands"]],
    ["01_shell", "シェル", $colors["linuxCommandsColor"]],
    ["02_filesystem", "ファイルシステム", $colors["linuxCommandsColor"]],
    ["03_operatingsystem", "システム管理", $colors["linuxCommandsColor"]],
    ["04_fileediting", "ファイル編集", $colors["linuxCommandsColor"]],
    ["05_networking", "ネットワーク", $colors["linuxCommandsColor"]],
    ["06_graphics", "画像・印刷", $colors["linuxCommandsColor"]],
    ["07_multimedia", "動画・音声", $colors["linuxCommandsColor"]],

    ["slackware", "Slackware", $colors["slackware"]],
    ["01_settings", "設定", $colors["slackwareColor"]],
    ["02_fixing", "修理", $colors["slackwareColor"]],

    ["beginners", "Linux入門", $colors["beginners"]],
    ["01_reason", "Linuxを始める理由", $colors["beginnersColor"]],
    ["02_methods", "Linux導入の方法", $colors["beginnersColor"]],
    ["03_afterinstall", "Linux導入後にやりたいこと", $colors["beginnersColor"]],
    ["04_troubleshooting", "トラブル対策", $colors["beginnersColor"]],
];    

