<?php
require_once("ClassMiel.php");

$oMiel = new ClassMiel();
$miels = $oMiel->getTypeOfMiels();
echo "<div class='tous-les-produits'>";
foreach ($miels as $miel) {
    $nom = htmlspecialchars($miel['type']);
    $photo = htmlspecialchars($miel['photo']);
    echo "<div class='produit'>";
    echo "<div>".$nom."</div>";
    echo "<img class='img-produit' src='".$photo."' alt='Pot de miel ".$nom."' width='100' height='150' loading='lazy'>";
    echo "</div>";
}
echo "</div>"
?>
