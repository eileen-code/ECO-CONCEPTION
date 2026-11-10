
<?php
require_once(__DIR__ . "/adminMiel.php");
require_once(__DIR__ . "/ClassConnexion.php");
startSecureSession();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

if (!isValidAdminCsrfToken($_POST['csrf_token'] ?? null)) {
    $_SESSION['login_error'] = 'Formulaire expiré. Veuillez réessayer.';
    header('Location: ../index.php');
    exit;
}

// Vérifier le type et la taille avant l’authentification.
$login = $_POST['login'] ?? null;
$password = $_POST['motdepasse'] ?? null;
if (!is_string($login) || !is_string($password)
    || strlen($login) > 255 || strlen($password) > 1024) {
    $_SESSION['login_error'] = 'Identifiant ou mot de passe incorrect.';
    header('Location: ../index.php');
    exit;
}

try {
    $oConnexion = new ClassConnexion();
    $status = $oConnexion->connect($login, $password);
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    $_SESSION['login_error'] = 'Connexion temporairement indisponible. Réessayez plus tard.';
    header('Location: ../index.php');
    exit;
} catch (RuntimeException $exception) {
    error_log($exception->getMessage());
    $_SESSION['login_error'] = 'Connexion temporairement indisponible. Réessayez plus tard.';
    header('Location: ../index.php');
    exit;
}

if ($status) {
    // Renouveler l’identifiant après la connexion.
    session_regenerate_id(true);
    $_SESSION['user'] = trim($login);
    unset($_SESSION['csrf_token'], $_SESSION['login_error']);
    adminCsrfToken();
    header('Location: ../backend.php');
    exit;
}

$_SESSION['login_error'] = 'Identifiant ou mot de passe incorrect.';
header('Location: ../index.php');
exit;
