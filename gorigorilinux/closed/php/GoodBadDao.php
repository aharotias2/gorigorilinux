<?php
require_once("MyPDO.php");
require_once("Logger.php");

class GoodBadDao {
    private $getSql = "SELECT good, bad FROM goodbad WHERE article_name = :article_name";
    private $insertSql = "INSERT INTO goodbad (article_name, good, bad) VALUES (:article_name, :good, :bad)";
    private $updateSql = "UPDATE goodbad SET good = :good, bad = :bad WHERE article_name = :article_name";

    public function get($articleName) {
	global $logger;
	global $pdo;
        $data = null;
        try {
            $stmt = $pdo->prepare($this->getSql);
            if ($stmt === false) {
		$logger->error("SQL ERROR: " . implode("; ", $stmt->errorInfo()) . "\n");
            } else {
		$logger->info("SQL: " . $this->getSql);
		$logger->info("PARAM: article_name = $articleName");
                $result = $stmt->execute([
                    ':article_name' => $articleName
                ]);
                if (!$result) {
		    $logger->info("ROW: " . implode("; ", $stmt->errorInfo()));
                } else {
                    $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    if (count($rows) == 0) {
			$logger->info("ROW: none");
                    } else {
                        $data = [
                            'good' => $rows[0]['good'],
                            'bad' => $rows[0]['bad']
                        ];
			$logger->info("ROW: article_name = $articleName, "
				    . "good = {$rows[0]['good']}, bad = {$rows[0]['bad']}");
                    }
                }
            }
        } catch (PDOException $e) {
	    $logger->error("ROW: EXCEPTION");
        }
        return $data;
    }

    public function insert($articleName, $good, $bad) {
	global $logger;
	global $pdo;
	$logger->info("SQL: " . $this->insertSql . ", "
		    . " articleName: $articleName, good: $good, bad: $bad");
        $stmt = $pdo->prepare($this->insertSql);
        $result = $stmt->execute([
            ':article_name' => $articleName,
            ':good' => $good,
            ':bad' => $bad
        ]);
        return $result;
    }

    public function update($articleName, $good, $bad) {
	global $logger;
	global $pdo;
	$logger->info("SQL: " . $this->updateSql);
	$stmt = $pdo->prepare($this->updateSql);
	$result = $stmt->execute([
	    ':good' => $exists['good'] + $good,
	    ':bad' => $exists['bad'] + $bad,
	    ':article_name' => $articleName
	]);
	return $result;
    }
}
