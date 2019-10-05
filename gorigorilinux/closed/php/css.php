<style type="text/css">
 <?php include("css/common.css"); ?>
</style>
<link rel="stylesheet" type="text/css" media="(max-width:500px)" href="css/layout-s.css">
<link rel="stylesheet" type="text/css" media="(min-width:501px) and (max-width:1200px)" href="css/layout-m.css">
<link rel="stylesheet" type="text/css" media="(min-width:1201px)" href="css/layout-l.css">
<link rel="stylesheet" type="text/css" media="(max-width:500px)" href="css/basic-s.css">
<link rel="stylesheet" type="text/css" media="(min-width:501px) and (max-width:1200px)" href="css/basic-m.css">
<link rel="stylesheet" type="text/css" media="(min-width:1201px)" href="css/basic-l.css">
<style type="text/css">
 <?php include("css/header-menu.css"); ?>
 <?php include("css/skin3.css"); ?>
</style>
<script type="text/javascript" src="js/syntax-hiliting.js"></script>
 <script type="text/javascript">
 <?php include("js/common.js"); ?>
 console.log("<?php echo basename($_SERVER['SCRIPT_NAME']); ?>");
 <?php
 switch (basename($_SERVER['SCRIPT_NAME'])) {
     case 'article.php':
         include("js/article.js");
         break;
     case 'index.php':
         include("js/index.js");
         break;
     case 'rsseditor.php':
         break;
     default:
         include("js/basic.js");
         break;
 }
 ?>
</script>
