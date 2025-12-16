<?php
require_once __DIR__ . '/../config/Database.php';

class Medecin {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance();
    }
    
    public function getAll() {
        $sql = "SELECT m.*, d.nom as department_nom 
                FROM medecins m 
                LEFT JOIN departments d ON m.department_id = d.id 
                ORDER BY m.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $sql = "SELECT m.*, d.nom as department_nom 
                FROM medecins m 
                LEFT JOIN departments d ON m.department_id = d.id 
                WHERE m.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

     public function create($data) {
        $sql = "INSERT INTO medecins (nom, prenom, specialite, department_id, email, telephone) 
                VALUES (:nom, :prenom, :specialite, :department_id, :email, :telephone)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }
}