<?php
require_once(__DIR__ . "/adminMiel.php");
requireAdminSession();

if ($_SERVER['REQUEST_METHOD'] !== 'POST'
    || !isValidAdminCsrfToken($_POST['csrf_token'] ?? null)) {
    header('Location: ../backend.php');
    exit;
}

$_SESSION = [];
// Supprimer aussi le cookie de session du navigateur.
if (ini_get('session.use_cookies')) {
    $cookie = session_get_cookie_params();
    setcookie(session_name(), '', [
        'expires' => time() - 42000,
        'path' => $cookie['path'],
        'domain' => $cookie['domain'],
        'secure' => $cookie['secure'],
        'httponly' => $cookie['httponly'],
        'samesite' => $cookie['samesite'] ?? 'Lax'
    ]);
}
session_destroy();
header('Location: ../index.php');
exit;
?>