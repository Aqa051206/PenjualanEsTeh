<?php
class Keranjang {
    private $conn;
    private $table_name = "keranjang";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function add($idPembeli, $idmenu) {
        $query = "SELECT jumlah FROM " . $this->table_name . " WHERE idPembeli = :idPembeli AND idmenu = :idmenu";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':idPembeli', $idPembeli);
        $stmt->bindParam(':idmenu', $idmenu);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $jumlahBaru = $row['jumlah'] + 1;

            $update = "UPDATE " . $this->table_name . " SET jumlah = :jumlah WHERE idPembeli = :idPembeli AND idmenu = :idmenu";
            $stmtUpdate = $this->conn->prepare($update);
            $stmtUpdate->bindParam(':jumlah', $jumlahBaru);
            $stmtUpdate->bindParam(':idPembeli', $idPembeli);
            $stmtUpdate->bindParam(':idmenu', $idmenu);
            return $stmtUpdate->execute();
        } else {
            $insert = "INSERT INTO " . $this->table_name . " (idPembeli, idmenu, jumlah) VALUES (:idPembeli, :idmenu, 1)";
            $stmtInsert = $this->conn->prepare($insert);
            $stmtInsert->bindParam(':idPembeli', $idPembeli);
            $stmtInsert->bindParam(':idmenu', $idmenu);
            return $stmtInsert->execute();
        }
    }

    public function getByUser($idPembeli) {
        $query = "SELECT keranjang.idmenu, menu.namamenu, menu.harga, menu.gambar, keranjang.jumlah 
                  FROM " . $this->table_name . " keranjang 
                  JOIN menu ON keranjang.idmenu = menu.idmenu 
                  WHERE keranjang.idPembeli = :idPembeli";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':idPembeli', $idPembeli);
        $stmt->execute();
        return $stmt;
    }

    public function updateJumlah($idPembeli, $idmenu, $jumlah) {
        $query = "UPDATE keranjang SET jumlah = :jumlah 
              WHERE idPembeli = :idPembeli AND idmenu = :idmenu";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":jumlah", $jumlah);
        $stmt->bindParam(":idPembeli", $idPembeli);
        $stmt->bindParam(":idmenu", $idmenu);
        return $stmt->execute();
    }


    public function updateQuantity($idPembeli, $idmenu, $jumlah) {
        $query = "UPDATE " . $this->table_name . " SET jumlah = :jumlah WHERE idPembeli = :idPembeli AND idmenu = :idmenu";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':jumlah', $jumlah);
        $stmt->bindParam(':idPembeli', $idPembeli);
        $stmt->bindParam(':idmenu', $idmenu);
        return $stmt->execute();
    }

    public function delete($idPembeli, $idmenu) {
    $query = "DELETE FROM keranjang WHERE idPembeli = :idPembeli AND idmenu = :idmenu";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":idPembeli", $idPembeli);
    $stmt->bindParam(":idmenu", $idmenu);
    return $stmt->execute();
    }

}
?>