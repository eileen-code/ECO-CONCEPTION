<?php
require_once(__DIR__ . "/ClassProduitDerive.php");

$oProduitDerive = new ClassProduitDerive();
$produitsDerives = $oProduitDerive->getAll();
echo "<div class='tous-les-produits'>";
foreach ($produitsDerives as $produit) {
    $nom = htmlspecialchars($produit['produit'], ENT_QUOTES, 'UTF-8');
    $photo = htmlspecialchars($produit['photo'], ENT_QUOTES, 'UTF-8');
    $webpPhoto = preg_replace('/\.(jpe?g|png)$/i', '.webp', $produit['photo']);
    $webpFile = dirname(__DIR__) . DIRECTORY_SEPARATOR
        . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $webpPhoto);

    echo "<figure class='produit'>";
    // Utiliser WebP si disponible, avec l’image originale en secours.
    if ($webpPhoto !== $produit['photo'] && is_file($webpFile)) {
        $webpSource = htmlspecialchars($webpPhoto, ENT_QUOTES, 'UTF-8');
        echo "<picture><source srcset='".$webpSource."' type='image/webp'>";
        echo "<img class='img-produit' src='".$photo."' alt='".$nom."' width='100' height='100' loading='lazy' decoding='async'></picture>";
    } else {
        echo "<img class='img-produit' src='".$photo."' alt='".$nom."' width='100' height='100' loading='lazy' decoding='async'>";
    }
    echo "<figcaption>".$nom."</figcaption>";
    echo "</figure>";
}
echo "</div>";
