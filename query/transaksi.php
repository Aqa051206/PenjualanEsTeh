<?php
class Transaksi {
    private $conn;
    private $table_name = "transaksi";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Buat transaksi utama
    public function createMainTransaksi($idPembeli, $total) {
        $query = "INSERT INTO " . $this->table_name . " (idpembeli, total) VALUES (:idpembeli, :total)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':idpembeli' => $idPembeli,
            ':total' => $total
        ]);
        return $this->conn->lastInsertId(); // ambil idtransaksi terakhir
    }

    // Tambahkan detail transaksi
    public function addDetail($idtransaksi, $idmenu, $jumlah, $hargasaatitu) {
    $query = "INSERT INTO transaksi_detail (idtransaksi, idmenu, jumlah, hargasaatitu)
              VALUES (:idtransaksi, :idmenu, :jumlah, :hargasaatitu)";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':idtransaksi', $idtransaksi);
    $stmt->bindParam(':idmenu', $idmenu);
    $stmt->bindParam(':jumlah', $jumlah);
    $stmt->bindParam(':hargasaatitu', $hargasaatitu);
    return $stmt->execute();
}

public function readAllTransaksiWithDetails() {
    $query = "
        SELECT 
            transaksi.idtransaksi,
            pembeli.nama AS nama,
            menu.namamenu,
            transaksi_detail.jumlah,
            transaksi_detail.hargasaatitu,
            (transaksi_detail.jumlah * transaksi_detail.hargasaatitu) AS subtotal
        FROM transaksi
        JOIN pembeli ON transaksi.idpembeli = pembeli.idpembeli
        JOIN transaksi_detail ON transaksi.idtransaksi = transaksi_detail.idtransaksi
        JOIN menu ON transaksi_detail.idmenu = menu.idmenu
        ORDER BY transaksi.idtransaksi DESC
    ";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}
}
?>
