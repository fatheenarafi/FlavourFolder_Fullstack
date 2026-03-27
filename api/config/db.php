<?php
require_once __DIR__ . "/../../includes/db.php";

if (!class_exists('Database')) {
    class Database {
        public $conn;
        public function connect() {
            $this->conn = getDB();
            return $this->conn;
        }
    }
}
?>
