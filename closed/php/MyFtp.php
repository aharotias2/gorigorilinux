<?php
require_once("Logger.php");

class MyFtp {
    private $host = "ftp.singersongwriter.ciao.jp";
    private $port = "21";
    private $timeout = "30";
    private $username = "ciao.jp-singersongwriter";
    private $password = "3Cats4Ever";
    private $conn;
    
    public function __construct() {
	global $logger;
	$this->conn = ftp_connect($this->host, $this->port, $this->timeout);
	if ($this->conn !== false) {
	    $logger->info("FTP connection succeeded.");
	    $loginResult = ftp_login($this->conn, $this->username, $this->password);
	    if ($loginResult) {
		$logger->info("FTP login suceeded.");
	    } else {
		$logger->error("FTP login failed!");
	    }
	} else {
	    $logger->error("FTP connection failed!");
	}
    }

    public function __destruct() {
	global $logger;
	ftp_close($this->conn);
	$logger->info("FTP connection closed");
    }
    
    public function uploadTextFile($filePath) {
	global $logger;
	$result = ftp_put($this->conn, "/" . $filePath, $filePath, FTP_ASCII);
	if ($result) {
	    $logger->info("FTP upload succeeded: $filePath");
	} else {
	    $logger->error("FTP upload failed: $filePath");
	}
	return $result;
    }

    public function uploadBinFile($filePath) {
	$result = ftp_put($this->conn, "/" . $filePath, $filePath, FTP_BINARY);
	if ($result) {
	    $logger->info("FTP upload succeeded: $filePath");
	} else {
	    $logger->error("FTP upload failed: $filePath");
	}
	return $result;
    }
}
