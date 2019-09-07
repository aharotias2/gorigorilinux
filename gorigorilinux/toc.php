#!/usr/bin/php-cgi
<?php
session_start();
$localPrefix = "closed/php/";
include($localPrefix . "functions.php");
$category = $_GET['category'];
$jaCategory = null;
foreach (explode("/", $category) as $name) {
    if (sizeof($jaCategory) > 0) {
        $jaCategory .= "/";
    }
    $name = translate($name);
    $jaCategory .= translate($name);
}
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="PICS-Label" content='(PICS-1.1 "http://www.classify.org/safesurf/" L gen true for "https://gorigorilinux.com" r (SS~~000 1 SS~~000 1))' />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php print($siteTitle); ?></title>
        <?php include("closed/php/css.php"); ?>
        <style type="text/css">
         <?php include("css/common.css"); ?>
         <?php include("css/skin3.css"); ?>
        </style>
        <script type="text/javascript">
         <?php include("js/common.js"); ?>
         <?php include("js/basic.js"); ?>
        </script>
    </head>
    <body>
        <?php include($localPrefix . "header.php"); ?>
        <?php if ($category != "") { ?>
            <div class="pankuzu">
                <?php ttPutPankuzu(ttGetParentCategory($category)); ?>
            </div>
        <?php } ?>
        <div class="contents">
            <div class="centerpane">
                <h3 class="toch3"><?php echo $jaCategory . ($jaCategory != "" ? "の" : "") . "記事一覧"; ?></h3>
                <div class="toc">
                    <?php ttPutToc($category, true, -1, true); ?>
                </div>
            </div>
            <div style="clear:both;"></div>
        </div>
        <?php include($localPrefix . "footer.php"); ?>
    </body>
</html>

