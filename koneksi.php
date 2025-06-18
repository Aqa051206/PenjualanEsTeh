<?php
class Database {
    private $host = "host";
    private $user = "user";
    private $passwd = "passwd";
    private $name = "name";
    private $port = port;

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
