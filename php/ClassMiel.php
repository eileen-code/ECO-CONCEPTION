<?php
require_once("ClassDatabase.php");

class ClassMiel {
    private ClassDatabase $db;

    public function __construct() {
        $this->db = new ClassDatabase();
    }

    public function getTypeOfMiels(): array {
        $dbConnect = $this->db->getConnection();
        $query = "SELECT type, photo FROM miel";
        $stmt = $dbConnect->query($query);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->disconnect();
        return $result;
    }
}
?>