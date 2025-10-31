<?php
/**
 * Model de Perfil
 */

class Perfil {
    private $conn;
    private $table = 'perfis';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Listar todos os perfis
    public function listar() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY nivel ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar por ID
    public function buscarPorId($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
