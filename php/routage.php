<?php
$pages = [
    'Accueil' => 'accueil.php',
    'Miels' => 'miels.php',
    'Produits dérivés' => 'produitsDerives.php',
    'Fabrication' => 'video.php',
    'Contact' => 'contact.php'
];

$route = $_GET['page'] ?? 'Accueil';
if (!is_string($route) || !isset($pages[$route])) {
    http_response_code(404);
    echo 'Page introuvable.';
    exit;
}

require __DIR__ . DIRECTORY_SEPARATOR . $pages[$route];
?>