<link rel="stylesheet" type="text/css" href="css/common.css">
<link rel="stylesheet" type="text/css" media="(max-width:500px)" href="css/layout-s.css">
<link rel="stylesheet" type="text/css" media="(min-width:501px) and (max-width:1200px)" href="css/layout-m.css">
<link rel="stylesheet" type="text/css" media="(min-width:1201px)" href="css/layout-l.css">
<link rel="stylesheet" type="text/css" media="(max-width:500px)" href="css/basic-s.css">
<link rel="stylesheet" type="text/css" media="(min-width:501px) and (max-width:1200px)" href="css/basic-m.css">
<link rel="stylesheet" type="text/css" media="(min-width:1201px)" href="css/basic-l.css">

<link rel="stylesheet" type="text/css" href="css/header-menu.css">
<link rel="stylesheet" type="text/css" href="css/skin3.css">

<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/syntax-hiliting.js"></script>
<?php
switch (basename($_SERVER['SCRIPT_NAME'])) {
    case 'article.php':
        echo '<script type="text/javascript" src="js/article.js"></script>';
        break;
    case 'index.php':
        echo '<script type="text/javascript" src="js/index.js"></script>';
        break;
    case 'rsseditor.php':
        break;
    default:
        echo '<script type="text/javascript" src="js/basic.js"></script>';
        break;
}
?>
