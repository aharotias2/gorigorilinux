<?php session_start(); ?>
<?php if ($_SESSION['role'] !== "admin") { ?>

    <!DOCTYPE html>
    <html lang="ja">
        <head><meta http-equiv="refresh" content="0; URL='index.php'" /></head>
        <body></body>
    </html>

<?php } else { ?>
    <?php
    require_once("closed/php/Logger.php");
    //require_once("closed/php/MyFtp.php");
    $rss = fopen("rss.xml", "w");
    if ($rss == false) {
        $log->error("open rss is failed");
        return;
    }
    
    function getParameter($paramName) {
        if (isset($_POST[$paramName])) {
            return $_POST[$paramName];
        } else {
            return null;
        }
    }

    $title = getParameter("title");
    $link = getParameter("link");
    $description = getParameter("description");
    $language = getParameter("language");
    $copyright = getParameter("copyright");
    $rating = getParameter("rating");
    $pub_date = getParameter("pub_date");
    $last_build_date = getParameter("last_build_date");
    $category = getParameter("category");
    $docs = getParameter("docs");
    $ttl = getParameter("ttl");
    $managing_editor = getParameter("managing_editor");
    $web_master = getParameter("web_master");
    $skip_hours = getParameter("skip_hours");
    $skip_days = getParameter("skip_days");
    $use_image = getParameter("use_image");
    $image_url = getParameter("image_url");
    $image_title = htmlspecialchars(getParameter("image_title"));
    $image_link = getParameter("image_link");
    $image_width = getParameter("image_width");
    $image_height = getParameter("image_height");
    $image_description = htmlspecialchars(getParameter("image_description"));
    
    fwrite($rss, "<?xml version=\"1.0\"?>\n");
    fwrite($rss, "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n");
    fwrite($rss, "<channel>\n");
    fwrite($rss, "  <atom:link href=\"https://gorigorilinux.net/rss.xml\" rel=\"self\" type=\"application/rss+xml\" />\n");
    fwrite($rss, "  <title>$title</title>\n");
    fwrite($rss, "  <link>$link</link>\n");
    fwrite($rss, "  <description>$description</description>\n");

    fwrite($rss, "  <language>$language</language>\n");
    fwrite($rss, "  <rating>$rating</rating>\n");
    fwrite($rss, "  <copyright>$copyright</copyright>\n");
    
    fwrite($rss, "  <pubDate>$pub_date</pubDate>\n");
    fwrite($rss, "  <lastBuildDate>$last_build_date</lastBuildDate>\n");
    
    fwrite($rss, "  <category>$category</category>\n");
    
    if (!empty($docs)) {
        fwrite($rss, "  <doc>$docs</doc>\n");
    }
    if (!empty($ttl)) {
        fwrite($rss, "  <ttl>$ttl</ttl>\n");
    }
    if (!empty($managing_editor)) {
        fwrite($rss, "  <managingEditor>$managing_editor</managingEditor>\n");
    }
    if (!empty($web_master)) {
        fwrite($rss, "  <webMaster>$web_master</webMaster>\n");
    }
    if (!empty($skip_hours)) {
        fwrite($rss, "  <skipHours>\n");
        foreach ($skip_hours as $skip_hour) {
            fwrite($rss, "    <hour>$skip_hour</hour>\n");
        }
        fwrite($rss, "  </skipHours>\n");
    }
    if (!empty($skip_days)) {
        fwrite($rss, "  <skipDays>\n");
        foreach ($skip_days as $skip_day) {
            fwrite($rss, "    <day>$skip_day</day>\n");
        }
        fwrite($rss, "  </skipDays>\n");
    }
    if ($use_image[0] == "true") {
        if (!(empty($image_title) || empty($image_url) || empty($image_link)
           || empty($image_width) || empty($image_height) || empty($image_description))) {
            fwrite($rss, <<<EOT
  <image>
    <title>$image_title</title>
    <url>$image_url</url>
    <link>$image_link</link>
    <width>$image_width</width>
    <height>$image_height</height>
    <description>$image_description</description>
  </image>
EOT
            );
        }
    }
    for ($i = 0; $i < 20; $i++) {
        $item_title = htmlspecialchars(getParameter('item_title' . $i));
        $item_link = getParameter('item_link' . $i);
        $item_description = htmlspecialchars(getParameter('item_description' . $i));
        $item_author = getParameter('item_author' . $i);
        $item_category = getParameter('item_category' . $i);
        $item_comment = getParameter('item_comment' . $i);
        $item_guid = getParameter('item_guid' . $i);
        $item_date = getParameter('item_publication_date' . $i);
        $item_source = getParameter('item_source' . $i);
        if (empty($item_title) || empty($item_link) || empty($item_description)) {
            continue;
        }
        fwrite($rss, "  <item>\n");
        fwrite($rss, "    <title>$item_title</title>\n");
        fwrite($rss, "    <link>$item_link</link>\n");
        fwrite($rss, "    <description>$item_description</description>\n");
        if (!empty($item_author)) {
            fwrite($rss, "    <author>$item_author</author>\n");
        }
        if (!empty($item_category)) {
            fwrite($rss, "    <category>$item_category</category>\n");
        }
        if (!empty($item_comment)) {
            fwrite($rss, "    <comments>$item_comment</comments>\n");
        }
        if (!empty($item_guid)) {
            fwrite($rss, "    <guid>$item_guid</guid>\n");
        }
        if (!empty($item_date)) {
            fwrite($rss, "    <pubDate>$item_date</pubDate>\n");
        }
        if (!empty($item_source)) {
            fwrite($rss, "    <source>$item_source</source>\n");
        }
        fwrite($rss, "  </item>\n");
    }
    fwrite($rss, "</channel>\n");
    fwrite($rss, "</rss>\n");
    fclose($rss);

    /*
    $ftp = new MyFtp();
    $ftp->uploadTextFile("rss.xml");
    */
    ?>
    <!DOCTYPE html>
    <html lang="ja">
        <head>
            <title>RSS書き出し完了</title>
            <?php include("closed/php/css.php"); ?>
        </head>
        <body>
            <h3>RSS書き出しが完了した。</h3>
            <p>以下の内容となる</p>
            <pre><?php
                 $f = fopen("rss.xml", "r");
                 while (($line = fgets($f)) != false) {
                     $line = htmlspecialchars($line);
                     echo $line;
                 }
                 fclose($f);
                 ?>
            </pre>
            <p><a href="rsseditor.php">戻る</a></p> 
        </body>
    </html>

<?php } ?>
