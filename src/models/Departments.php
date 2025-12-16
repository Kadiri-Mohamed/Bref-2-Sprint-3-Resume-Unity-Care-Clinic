<?php
require_once '../config/database.php';

class Department {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance();
    }

      public function getAll() {
        $sql = "SELECT * FROM departments ORDER BY nom ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM departments WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}