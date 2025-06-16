<?php
class Database {
    private $host = "sql210.infinityfree.com";
    private $user = "if0_39243975";
    private $passwd = "yQHo1wIY0uwxsch";
    private $name = "if0_39243975_db_esteh";
    private $port = 3306;

    private $conn;
     public function connection() {
        $this->conn = null;

        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->name}";
            $this->conn = new PDO($dsn, $this->user, $this->passwd);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
    throw new Exception("Koneksi ke database gagal: " . $exception->getMessage());
    }
        return $this->conn;
    }
}

?>