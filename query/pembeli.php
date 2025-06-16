<?php
class Pembeli {
    private $con;
    private $table = "pembeli";

    public $idPembeli, $nama, $email, $password, $hp, $alamat, $foto, $role;

    public function __construct($db) {
        $this->con = $db;
    }

    public function create() {
        $query = "INSERT INTO {$this->table} 
            (nama, email, password, hp, alamat, foto, role) 
            VALUES (:nama, :email, :password, :hp, :alamat, :foto, :role)";

        $statement = $this->con->prepare($query);
        $statement->bindParam(":nama", $this->nama);
        $statement->bindParam(":email", $this->email);
        $statement->bindParam(":password", $this->password);
        $statement->bindParam(":hp", $this->hp);
        $statement->bindParam(":alamat", $this->alamat);
        $statement->bindParam(":foto", $this->foto); // tambah ini
        $statement->bindParam(":role", $this->role);

        return $statement->execute();
    }

    public function update()
{
    $query ="UPDATE {$this->table} 
        SET nama = :nama, email = :email, password = :password, hp = :hp, alamat = :alamat, foto = :foto
        WHERE idPembeli = :idPembeli";

    $statement = $this->con->prepare($query);
    $statement->bindParam(":idPembeli", $this->idPembeli);
    $statement->bindParam(":nama", $this->nama);
    $statement->bindParam(":email", $this->email);
    $statement->bindParam(":password", $this->password);
    $statement->bindParam(":hp", $this->hp);
    $statement->bindParam(":alamat", $this->alamat);
    $statement->bindParam(":foto", $this->foto); // Tambahkan ini!
    return $statement->execute();
}

    public function readAll() {
        $query = "SELECT * FROM {$this->table}";
        $statement = $this->con->prepare($query);
        $statement->execute();
        return $statement;
    }

    public function readById($idPembeli) {
        $query = "SELECT * FROM pembeli WHERE idPembeli = :idPembeli";
        $statement = $this->con->prepare($query);
        $statement->bindParam(':idPembeli', $idPembeli);
        $statement->execute();
        return $statement;
    }

    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE idPembeli = :idPembeli";
        $statement = $this->con->prepare($query);
        $statement->bindParam(":idPembeli", $this->idPembeli);
        return $statement->execute();
    }

    public function cari($id) {
        $statement = $this->con->prepare("SELECT * FROM {$this->table} WHERE idPembeli = ?");
        $statement->execute([$id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}
?>
