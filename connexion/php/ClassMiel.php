<?php
require_once(__DIR__ . "/ClassDatabase.php");

class ClassMiel {
    private ClassDatabase $db;
    private PDO $pdo;

    public function __construct() {
        $this->db = new ClassDatabase();
        $this->pdo = $this->db->getConnection();
    }

    public function addMiel(string $type, string $photo): bool {
        $type = trim($type);
        $photo = trim($photo);

        if ($type === '' || $photo === '') {
            return false;
        }

        if (strlen($type) > 255 || strlen($photo) > 255) {
            return false;
        }

        $sql = "INSERT INTO miel (type, photo)
                VALUES (:type, :photo)";

        // Valeurs liées pour éviter l’injection SQL.
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'type' => $type,
            'photo' => $photo
        ]);
    }

    public function updateMiel(int $id, string $type, string $photo): bool {
        $type = trim($type);
        $photo = trim($photo);

        if ($id <= 0 || $type === '' || $photo === '') {
            return false;
        }

        if (strlen($type) > 255 || strlen($photo) > 255) {
            return false;
        }

        $sql = "UPDATE miel
                SET type = :type, photo = :photo
                WHERE id = :id";

        // Les champs modifiés et l’identifiant sont liés à la requête.
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'type' => $type,
            'photo' => $photo
        ]);
    }

    public function removeMiel(int $id): bool {
        if ($id <= 0) {
            return false;
        }

        $sql = "DELETE FROM miel WHERE id = :id";

        // L’identifiant reste un paramètre, même après validation numérique.
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }

    public function getMielById(int $id): ?array {
        if ($id <= 0) {
            return null;
        }

        $stmt = $this->pdo->prepare(
            "SELECT id, type, photo FROM miel WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
        $miel = $stmt->fetch(PDO::FETCH_ASSOC);

        return $miel === false ? null : $miel;
    }

    public function getListMiels(): array {
        // Requête fixe, sans donnée de formulaire.
        $sql = "SELECT id, type, photo
                FROM miel
                ORDER BY type";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
