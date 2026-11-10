<?php
require_once(__DIR__ . "/ClassDatabase.php");

class ClassMiel {
    private ClassDatabase $db;

    public function __construct() {
        $this->db = new ClassDatabase();
    }

    public function getTypeOfMiels(): array {
        $dbConnect = $this->db->getConnection();
        // Requête fixe, sans donnée utilisateur.
        $query = "SELECT id, type, photo FROM miel ORDER BY type";
        $stmt = $dbConnect->query($query);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->disconnect();
        return $result;
    }
}
?>