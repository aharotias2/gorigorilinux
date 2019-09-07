<?php
require_once("settings.php");
class Logger {
    private $fp;
    private $timeFormat = "Y/m/d H:i:s";

    private function getDate() {
        return date($this->timeFormat);
    }
    
    public function __construct($logPath) {
        $this->fp = fopen($logPath, "a");
    }

    public function __destruct() {
        fclose($this->fp);
    }
    
    public function error($message) {
        $t = date($this->timeFormat);
        fwrite($this->fp, "[$t] ERROR: " . $message . "\n");
    }

    public function warn($message) {
        $t = date($this->timeFormat);
        fwrite($this->fp, "[$t] WARN: " . $message . "\n");
    }
    
    public function info($message) {
        $t = date($this->timeFormat);
        fwrite($this->fp, "[$t] INFO: " . $message . "\n");
    }
}

if (!isset($logger)) {
    $logger = new Logger("log/error.log");
}

