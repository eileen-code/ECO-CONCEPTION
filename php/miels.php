<?php
require_once(__DIR__ . "/ClassMiel.php");

$oMiel = new ClassMiel();
$miels = $oMiel->getTypeOfMiels();
echo "<div class='tous-les-produits'>";
foreach ($miels as $miel) {
    $nom = htmlspecialchars($miel['type'], ENT_QUOTES, 'UTF-8');
    $photo = htmlspecialchars($miel['photo'], ENT_QUOTES, 'UTF-8');
    echo "<figure class='produit'>";
    echo "<img class='img-produit' src='".$photo."' alt='".$nom."' width='100' height='100' loading='lazy' decoding='async'>";
    echo "<figcaption>".$nom."</figcaption>";
    echo "</figure>";
}
echo "</div>";
?>