<?php
    class Menu {
        private $con;
        private $table = "menu";
        public $idmenu, $namamenu, $harga, $gambar;

        public function __construct($db) {
            $this->con = $db;
        }

        public function create() {
            $query = "INSERT INTO {$this->table} (namamenu, harga, gambar) VALUES (:namamenu, :harga, :gambar)";
            $statement = $this->con->prepare($query);
            $statement->bindParam(":namamenu", $this->namamenu);
            $statement->bindParam(":harga", $this->harga);
            $statement->bindParam(":gambar", $this->gambar);
            return $statement->execute();
        }

        public function update() {
            $query = "UPDATE {$this->table} SET namamenu = :namamenu, harga = :harga, gambar = :gambar WHERE idmenu = :idmenu";
            $statement = $this->con->prepare($query);
            $statement->bindParam(":namamenu", $this->namamenu);
            $statement->bindParam(":harga", $this->harga);
            $statement->bindParam(":gambar", $this->gambar);
            $statement->bindParam(":idmenu", $this->idmenu);
            return $statement->execute();
        }

        public function readAll() {
            $query = "SELECT * FROM {$this->table}";
            $statement = $this->con->prepare($query);
            $statement->execute();
            return $statement;
        }

        public function delete($idmenu) {
            $query = "DELETE FROM menu WHERE idmenu = :idmenu";
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':idmenu', $idmenu, PDO::PARAM_INT);
            return $stmt->execute();
        }


        public function cari($id) {
            $statement = $this->con->prepare("SELECT * FROM {$this->table} WHERE idmenu = ?");
            $statement->execute([$id]);
            return $statement->fetch(PDO::FETCH_ASSOC);
        }

        public function getById($id) {
            $query = "SELECT * FROM menu WHERE idmenu = :idmenu";
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':idmenu', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }


    }