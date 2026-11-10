<?php
/*
 * ECO-CONCEPTION (pilier Planete) - page Miels :
 *  - l'ancienne balise <img ...></img> etait invalide (<img> est une
 *    balise auto-fermante, elle ne peut pas avoir de fermante </img>) :
 *    retiree pour corriger les erreurs des validateurs W3C.
 *  - ajout de alt (accessibilite / WAVE), width/height (evite le
 *    redimensionnement affiche-navigateur reperee par GreenIT ET reserve
 *    l'espace pour eviter un saut de mise en page) et loading="lazy"
 *    (les images hors ecran ne sont telechargees qu'au scroll).
 */
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