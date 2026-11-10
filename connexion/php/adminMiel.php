<?php

/** Configure les en-têtes et le cookie de session. */
function startSecureSession(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.use_trans_sid', '0');

    $isHttps = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

    session_start();
}

function requireAdminSession(): void
{
    startSecureSession();

    if (!isset($_SESSION['user'])) {
        $scriptDirectory = str_replace(
            '\\',
            '/',
            dirname($_SERVER['SCRIPT_NAME'] ?? '/connexion/backend.php')
        );
        if (basename($scriptDirectory) === 'php') {
            $scriptDirectory = dirname($scriptDirectory);
        }
        header('Location: ' . rtrim($scriptDirectory, '/') . '/index.php');
        exit;
    }
}

function adminCsrfToken(): string
{
    // Jeton CSRF partagé par les formulaires de la session.
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function isValidAdminCsrfToken($token): bool
{
    // Comparaison à temps constant.
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function setAdminFlash(string $message, string $type = 'success'): void
{
    $_SESSION['admin_flash'] = [
        'message' => $message,
        'type' => $type
    ];
}

function redirectToAdmin(string $message, string $type = 'success'): void
{
    setAdminFlash($message, $type);
    header('Location: ../backend.php');
    exit;
}

function saveMielPhoto(array $upload): string
{
    if (!isset($upload['error']) || is_array($upload['error'])) {
        throw new RuntimeException('Le fichier envoyé est invalide.');
    }

    if ($upload['error'] !== UPLOAD_ERR_OK) {
        if ($upload['error'] === UPLOAD_ERR_NO_FILE) {
            throw new RuntimeException('Choisissez une image à envoyer.');
        }

        throw new RuntimeException('L’envoi de l’image a échoué.');
    }

    if (!isset($upload['size']) || $upload['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException('L’image ne doit pas dépasser 5 Mo.');
    }

    if (!isset($upload['tmp_name']) || !is_uploaded_file($upload['tmp_name'])) {
        throw new RuntimeException('Le fichier envoyé est invalide.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($upload['tmp_name']);
    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp'
    ];

    if (!isset($extensions[$mimeType])) {
        throw new RuntimeException('Format non accepté. Utilisez JPG, PNG, GIF ou WebP.');
    }

    $imageInfo = getimagesize($upload['tmp_name']);
    if ($imageInfo === false || $imageInfo['mime'] !== $mimeType) {
        throw new RuntimeException('Le fichier choisi n’est pas une image valide.');
    }

    // Limite aussi les dimensions pour éviter les images démesurées.
    if ($imageInfo[0] > 8000 || $imageInfo[1] > 8000
        || ($imageInfo[0] * $imageInfo[1]) > 40000000) {
        throw new RuntimeException('Les dimensions de l’image sont trop importantes.');
    }

    $uploadDirectory = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR
        . 'img' . DIRECTORY_SEPARATOR . 'miels';

    if (!is_dir($uploadDirectory) || !is_writable($uploadDirectory)) {
        throw new RuntimeException('Le dossier des images est indisponible en écriture.');
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mimeType];
    $destination = $uploadDirectory . DIRECTORY_SEPARATOR . $filename;

    if (!move_uploaded_file($upload['tmp_name'], $destination)) {
        throw new RuntimeException('Impossible d’enregistrer l’image envoyée.');
    }

    return 'img/miels/' . $filename;
}
