<?php
require_once(__DIR__ . "/ClassDatabase.php");

class ClassConnexion {
    private ClassDatabase $db;

    public function __construct() {
        $this->db = new ClassDatabase();
    }

    public function connect($pLogin, $pPassword): bool {
        if (!is_string($pLogin) || !is_string($pPassword)) {
            return false;
        }

        $login = trim($pLogin);
        $password = $pPassword;

        if ($login === '' || $password === '') {
            return false;
            exit;
        }

        if (strlen($login) > 255 || strlen($password) > 1024) {
            return false;
            exit;
        }

        $pdo = $this->db->getConnection();

        $sql = "SELECT login, motdepasse
                FROM utilisateur
                WHERE login = :login
                LIMIT 1";

        // Le login est lié comme donnée, jamais injecté dans la requête.
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'login' => $login
        ]);

        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        $result = $utilisateur !== false
            && password_verify($password, $utilisateur['motdepasse']);

        if ($result && password_needs_rehash($utilisateur['motdepasse'], PASSWORD_DEFAULT)) {
            $rehash = $pdo->prepare(
                "UPDATE utilisateur SET motdepasse = :motdepasse WHERE login = :login"
            );
            $rehash->execute([
                'motdepasse' => password_hash($password, PASSWORD_DEFAULT),
                'login' => $login
            ]);
        }

        $this->db->disconnect();
        return $result;
    }
}
?>