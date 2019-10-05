<?php
require_once("MyPDO.php");
require_once("Logger.php");

class CommentsDao {
    private $insertSql = "INSERT INTO comments "
                       . "(article_name, comment_no, user_name, mail_address, comment, "
                       . "anchor, post_date, delete_flag) "
                       . "VALUES (:article_name, "
                       . "(SELECT COUNT(*) + 1 FROM comments WHERE article_name = :article_name),"
                       . ":user, :mail, :comment, :anchor, datetime('now', '+9 hours'), 0)";

    private $commentCountSql = "SELECT article_name, count(*) AS comments_count "
                             . "FROM comments WHERE delete_flag = 0 GROUP BY article_name";

    private $getCommentSql = "SELECT comment_no, user_name, mail_address, comment, anchor, post_date "
                           . "FROM comments "
                           . "WHERE article_name = :article_name AND anchor = :anchor AND delete_flag = 0 "
                           . "ORDER BY post_date ASC";

    private $deleteSql = "UPDATE comments SET delete_flag = 1 "
                       . "WHERE article_name = :article_name AND comment_no = :comment_no";

    private $recentCommentsSql = "SELECT article_name, user_name, post_date FROM comments "
                               . "ORDER BY post_date desc LIMIT ";
    
    public function insert($articleName, $user, $mail, $comment, $anchor) {
        global $logger;
        global $pdo;
        $logger->info("SQL: $sql");
        $stmt = $pdo->prepare($this->insertSql);
        $params = [
            ':article_name' => $articleName,
            ':user' => $user,
            ':mail' => $mail,
            ':comment' => $comment,
            ':anchor' => $anchor
        ];
        $result = $stmt->execute($params);
        $logger->info("SQL: executed[" . ($result ? "SUCCESS" : "FAILED") . "] with [" . implode(", ", $params) . "]");
        if (!$result) {
            $logger->error("SQL: " . implode("; ", $stmt->errorInfo()));
        }
    }

    public function getCommentsCount() {
        global $pdo;
        $stmt = $pdo->query($this->commentCountSql);
        $result = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $result[$row['article_name']] = $row['comments_count'];
        }
        return $result;
    }

    public function getRecentComments($limit) {
	global $pdo;
	$stmt = $pdo->query($this->getRecentCommentsSql + $limit);
	$result = [];
	while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
	    $result[] = [
		'article_name' => $row['article_name'],
		'user_name' => $row['user_name'],
		'post_date' => $row['post_date']
	    ];
	}
	return $result;
    }
    
    public function getComment($articleName, $anchor) {
        global $logger;
        global $pdo;
        $logger->info("SQL: " . $this->getCommentSql);
        $stmt = $pdo->prepare($this->getCommentSql);
        $result = $stmt->execute([
            ':article_name' => $articleName,
            ':anchor' => $anchor
        ]);
        if (!$result) {
            $logger->error("SQL FAILED: " . implode("; ", $stmt->errorInfo()));
        } else {
            $result = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $result[] = [
                    'comment_no' => $row['comment_no'],
                    'user_name' => $row['user_name'],
                    'mail_address' => $row['mail_address'],
                    'comment' => $row['comment'],
                    'anchor' => $row['anchor'],
                    'post_date' => $row['post_date']
                ];
            }
        }
        return $result;
    }

    public function delete($articleName, $anchor) {
        global $pdo;
        $stmt = $pdo->prepare($this->deleteSql);
        $stmt->execute([
            ':article_name' => $articleName,
            ':comment_no' => $anchor
        ]);
    }
}
