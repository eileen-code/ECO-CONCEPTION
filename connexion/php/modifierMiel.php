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
$type = $_POST['type'] ?? null;

if ($id === false || $id === null || $id <= 0) {
    redirectToAdmin('Le miel à modifier est invalide.', 'error');
}

if (!is_string($type) || trim($type) === '' || strlen(trim($type)) > 255) {
    redirectToAdmin('Saisissez un type de miel valide (255 caractères maximum).', 'error');
}

try {
    $miel = new ClassMiel();
    $existingMiel = $miel->getMielById($id);
    if ($existingMiel === null) {
        redirectToAdmin('Le miel demandé n’existe plus.', 'error');
    }

    $photo = $existingMiel['photo'];
    $upload = $_FILES['photo'] ?? null;
    if (is_array($upload) && ($upload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
        $photo = saveMielPhoto($upload);
    }

    if (!$miel->updateMiel($id, $type, $photo)) {
        throw new RuntimeException('La base de données a refusé la modification du miel.');
    }
} catch (RuntimeException $exception) {
    error_log($exception->getMessage());
    redirectToAdmin($exception->getMessage(), 'error');
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    redirectToAdmin('Impossible d’enregistrer les modifications du miel.', 'error');
}

redirectToAdmin('Les modifications du miel ont été enregistrées.');
?>
