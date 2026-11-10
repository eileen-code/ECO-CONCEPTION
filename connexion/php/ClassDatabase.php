<?php

class ClassDatabase
{
    private ?PDO $pdo = null;

    // En production, définir les identifiants avec les variables MELLIFICA_DB_*.
    private string $host;
    private int $port;
    private string $dbname;
    private string $username;
    private string $password;
    private string $charset = 'utf8mb4';

    public function __construct()
    {
        $this->host = getenv('MELLIFICA_DB_HOST') ?: 'localhost';
        $this->port = (int) (getenv('MELLIFICA_DB_PORT') ?: 3306);
        $this->dbname = getenv('MELLIFICA_DB_NAME') ?: 'mellifica';
        $this->username = getenv('MELLIFICA_DB_USER') ?: 'root';
        $this->password = getenv('MELLIFICA_DB_PASSWORD') ?: '';
    }

    /**
     * Retourne la connexion courante (ou la crée si besoin).
     */
    public function getConnection(): PDO
    {
        if ($this->pdo === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                $this->host,
                $this->port,
                $this->dbname,
                $this->charset
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Exceptions en cas d'erreur
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Résultats en tableau associatif
                PDO::ATTR_EMULATE_PREPARES   => false,                  // Vraies requêtes préparées
            ];

            try {
                $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
            } catch (PDOException $e) {
                // Détail réservé au journal serveur.
                error_log($e->getMessage());
                throw new RuntimeException('Erreur de connexion à la base de données.');
            }
        }

        return $this->pdo;
    }

    /**
     * Ferme explicitement la connexion.
     */
    public function disconnect(): void
    {
        $this->pdo = null;
    }

    /**
     * Permet de savoir si la connexion est déjà ouverte.
     */
    public function isConnected(): bool
    {
        return $this->pdo !== null;
    }
}