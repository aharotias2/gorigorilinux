<?php
$siteTitle = "ごりごりりぬっくす.net";
date_default_timezone_set("Asia/Tokyo");
// 色の設定
$linuxCommandsColor = "olive";
$itNewsMonthColor = "darkorange";
$othersColor = "mediumvioletred";
$lifeColor = "turquoise";
$slackwareColor = "royalblue";
$beginnersColor = "red";
$normalNewsColor = "olive";
$diaryColor = "darkmagenta";
$linuxAppsColor = "darkorange";
$cLangColor = "#C1AB05";

$translations = [
    // プログラミング
    ["prog", "プログラミング", "mediumblue"],

    ["c", "C言語", "#4d4308"], //slate green
    ["01_prolog", "準備", $cLangColor],

    // アプリケーション
    ["apps", "アプリケーション", "darkcyan"],

    ["linux", "Linux", "blueviolet"],
    ["01_system", "システム系", $linuxAppsColor],

    // ITニュース
    ["itnews", "ITニュース", "seagreen"],

    ["2019", "2019年", "chocolate"],
    ["03_march", "3月", $itNewsMonthColor],
    ["04_april", "4月", $itNewsMonthColor],
    ["05_may", "5月", $itNewsMonthColor],
    ["06_june", "6月", $itNewsMonthColor],

    // 雑文
    ["misc", "雑文", "#EF857D"],

    ["self", "私事", "darkolivegreen"],
    ["01_self", "自己紹介", $othersColor],
    ["02_gadgets", "持ち物", $othersColor],
    ["03_old-stories", "思い出話", $othersColor],
    ["04_life", "生活", $othersColor],
    ["05_social", "社会", $othersColor],
    ["06_adult", "アダルト", $othersColor],

    ["diary", "日記", "sienna"],
    ["01_201905", "2019年5月", $diaryColor],
    ["02_201907", "2019年7月", $diaryColor],

    ["news", "ニュース (IT、Linux関係ない)", "sienna"],
    ["01_201906", "2019年6月", $normalNewsColor],

    // Linux
    ["linux", "Linux", "darkolivegreen"],

    ["commands", "Linuxコマンド", "teal"],
    ["01_shell", "シェル", $linuxCommandsColor],
    ["02_filesystem", "ファイルシステム", $linuxCommandsColor],
    ["03_operatingsystem", "システム管理", $linuxCommandsColor],
    ["04_fileediting", "ファイル編集", $linuxCommandsColor],
    ["05_networking", "ネットワーク", $linuxCommandsColor],
    ["06_graphics", "画像・印刷", $linuxCommandsColor],
    ["07_multimedia", "動画・音声", $linuxCommandsColor],

    ["slackware", "Slackware", "dimgray"],
    ["01_settings", "設定", $slackwareColor],

    ["beginners", "Linux入門", "deeppink"],
    ["01_reason", "Linuxを始める理由", $beginnersColor],
    ["02_methods", "Linux導入の方法", $beginnersColor],
    ["03_afterinstall", "Linux導入後にやりたいこと", $beginnersColor],
    ["04_troubleshooting", "トラブル対策", $beginnersColor],
];    

