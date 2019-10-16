<?php
require_once("Logger.php");
require_once("CommentsDao.php");
require_once("GoodBadDao.php");
require_once("MyMarkdown.php");

class SQLiteClient {
    private $dns;

    public function __construct($sqlite_dns = null) {
        $this->nds = $sqlite_dns != null ? $sqlite_dns : "sqlite:db/phpsqlite.db";
    }

    public function addGoodbad($articleName, $good, $bad) {
	global $logger;
        $dao = new GoodBadDao($this->dns);
        $exists = $dao->get($articleName);
	$logger->info("GOODBAD exists: " . ($exists != null ? "true" : "false") . "\n");
        if ($exists == null) {
            $result = $dao->insert($articleName, $good, $bad);
        } else {
            $result = $dao->update($articleName, $exists['good'] + $good, $exists['bad'] + $bad);
        }
    }        

    public function putGoodbad($articleName, $good, $bad) {
        try {
            $dao = new GoodBadDao($this->dns);
            $goodbad = $dao->get($articleName);
            echo "<p style=\"font-size:small;color:#808890;margin:2px 5px\">";
	    echo "いいね・よくないねボタンについて: このボタンはあなたの個人情報を一切記録しません。";
	    echo "24時間ごとに「いいね」か「よくないね」のどちらか1点まで押せます。";
	    echo "2回同じボタンを押すと±0になります。安心して連打してください。</p>";
            if ($good == 0) {
                echo "  <button id=\"good-button\"><img src=\"images/like.png\">";
            } else {
                echo "  <button id=\"good-button\" class=\"goodbad-pressed\"><img src=\"images/like.png\">";
            }
            $good2 = $goodbad !== null ? $goodbad['good'] : 0;
            echo "いいね: <span class=\"count\">$good2</span></button>";

            if ($bad == 0) {
                echo "  <button id=\"bad-button\"><img src=\"images/dislike.png\">";
            } else {
                echo "  <button id=\"bad-button\" class=\"goodbad-pressed\"><img src=\"images/dislike.png\">";
            }
            $bad2 = $goodbad !== null ? $goodbad['bad'] : 0;
            echo "よくないね: <span class=\"count\">$bad2</span></button>";
        } catch (PDOException $e) {
            echo "<div class=\"goodbad\">\n";
            echo "<p>コメントを取得できませんでした。</p>\n";
            echo "</div>\n";
        }
    }

    public function putComments($articleName, $commentNo = 0) {
	global $logger;
        try {
            $dao = new CommentsDao($this->dns);
            $comments = $dao->getComment($articleName, $commentNo);
            if ($commentNo == 0 && sizeof($comments) == 0) {
                echo "<div class=\"no_comments\">コメントはまだありません。</div>";
            } else {
                foreach ($comments as $comment) {
                    echo "<div class=\"comment_post article\">\n";
                    echo "  <div class=\"comment_box\">\n";
		    echo "    <div class=\"comment_header\">\n";
                    echo "      <div class=\"comment_user\">" . $comment['user_name'] . "</div>\n";
                    echo "      <div class=\"comment_mail\">" . $comment['mail_address'] . "</div>\n";
                    echo "      <div class=\"comment_date\">" . $comment['post_date'] . "</div>\n";
		    echo "    </div>\n";
                    echo "    <div class=\"comment_text\">";
		    echo MyMarkdown::getMarkdownFromText($comment['comment'] . "\n \n", false /* not allow html */);
		    echo "</div>\n";
                    if ($commentNo == 0) {
                        echo "    <div class=\"reply_block\">\n";
                        echo "      <button class=\"reply_button\" value=\"" . $comment['comment_no'] . "\">返信</button>\n";
                        if ($_SESSION['role'] == "admin") {
                            echo "      <button class=\"delete_button\" value=\"" . $comment['comment_no'] . "\">削除</button>\n";
                        }
                        echo "    </div>\n";
                        echo "  </div>\n";
                        echo "  <div class=\"comment_reply\">\n";
                        $this->putComments($articleName, $comment["comment_no"]);
                        echo "  </div>\n";
                    } else {
                        if ($_SESSION['role'] == "admin") {
                            echo "    <div class=\"reply_block\">\n";
                            echo "      <button class=\"delete_button\" value=\"" . $comment['comment_no'] . "\">削除</button>\n";
                            echo "    </div>\n";
                        }
                        echo "  </div>\n";
                    }
                    echo "</div>\n";
                }
            }
        } catch (\PDOException $e) {
	    $logger->error($e->message);
            echo "<div class=\"comment\">\n";
            echo "<p>コメントを取得できませんでした。</p>\n";
            echo "</div>\n";
        }
    }
}
