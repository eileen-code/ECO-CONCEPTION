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

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id <= 0) {
    redirectToAdmin('Le miel à supprimer est invalide.', 'error');
}

try {
    $miel = new ClassMiel();
    if (!$miel->removeMiel($id)) {
        redirectToAdmin('Aucun miel correspondant n’a été supprimé.', 'error');
    }
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    redirectToAdmin('Impossible de supprimer le miel.', 'error');
}

redirectToAdmin('Le miel a été supprimé.');
?>