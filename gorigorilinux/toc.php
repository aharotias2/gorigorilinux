<?php
session_start();
$localPrefix = "closed/php/";
include($localPrefix . "functions.php");
$category = $_GET['category'];
$jaCategory = null;
foreach (explode("/", $category) as $name) {
    if (count($jaCategory) > 0) {
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
    </head>
    <body>
        <?php include($localPrefix . "header.php"); ?>
        <div class="contents">
            <div class="centerpane">
                <h3 class="toch3">
		    <?php if ($category != "") { ?>
			<?php MyHTMLUtils::putPankuzu($category); ?>
		    <?php } else { ?>
			全ての記事
		    <?php } ?>
		</h3>
                <div class="toc">
		    <?php MyHTMLUtils::putToc($category, true, -1, true); ?>
                </div>
            </div>
            <div style="clear:both;"></div>
        </div>
        <?php include($localPrefix . "footer.php"); ?>
    </body>
</html>

