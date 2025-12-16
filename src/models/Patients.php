<?php
require_once '../config/database.php';

class Patient {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance();
    }
    
    public function getAll() {
        $sql = "SELECT * FROM patients ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM patients WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO patients (nom, prenom, date_naissance, telephone, email, adresse) 
                VALUES (:nom, :prenom, :date_naissance, :telephone, :email, :adresse)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }
    
}
?>