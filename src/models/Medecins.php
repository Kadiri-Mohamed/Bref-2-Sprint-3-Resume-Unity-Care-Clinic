<?php
require_once '../config/database.php';

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
    public function update($id, $data) {
        $sql = "UPDATE medecins 
                SET nom = :nom, 
                    prenom = :prenom, 
                    specialite = :specialite, 
                    department_id = :department_id, 
                    email = :email, 
                    telephone = :telephone 
                WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

     public function delete($id) {
        $sql = "DELETE FROM medecins WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM medecins";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

      public function getByDepartment($department_id) {
        $sql = "SELECT * FROM medecins WHERE department_id = :department_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':department_id', $department_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}