<?php
require_once(__DIR__ . "/adminMiel.php");
require_once(__DIR__ . "/ClassMiel.php");
requireAdminSession();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectToAdmin('Méthode de requête invalide.', 'error');
}

if (!isValidAdminCsrfToken($_POST['csrf_token'] ?? null)) {
    redirectToAdmin('La session du formulaire a expiré. Réessayez.', 'error');
}

$type = $_POST['type'] ?? null;
if (!is_string($type) || trim($type) === '' || strlen(trim($type)) > 255) {
    redirectToAdmin('Saisissez un type de miel valide (255 caractères maximum).', 'error');
}

try {
    $photo = saveMielPhoto($_FILES['photo'] ?? []);
    $miel = new ClassMiel();
    if (!$miel->addMiel($type, $photo)) {
        throw new RuntimeException('La base de données a refusé l’ajout du miel.');
    }
} catch (RuntimeException $exception) {
    error_log($exception->getMessage());
    redirectToAdmin($exception->getMessage(), 'error');
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    redirectToAdmin('Impossible d’enregistrer le miel dans la base de données.', 'error');
}

redirectToAdmin('Le miel a été ajouté.');
