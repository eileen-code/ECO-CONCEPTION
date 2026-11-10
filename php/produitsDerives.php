<?php
require_once("ClassProduitDerive.php");

$oProduitDerive = new ClassProduitDerive();
$produitsDerives = $oProduitDerive->getAll();

// MODIFICATION 1 : Ajout d'un titre principal h1
// pour améliorer la structure des titres de la page.
echo "<h1>Découvrez nos produits dérivés</h1>";

// MODIFICATION 2 : Création du conteneur des produits.
echo "<div class='tous-les-produits'>";

foreach ($produitsDerives as $produit) {

    // MODIFICATION 3 : Protection des données affichées
    // avec htmlspecialchars et déclaration de l'encodage UTF-8.
    $nom = htmlspecialchars($produit['produit'] ?? '', ENT_QUOTES, 'UTF-8');
    $photo = htmlspecialchars($produit['photo'] ?? '', ENT_QUOTES, 'UTF-8');

    echo "<div class='produit'>";

    // MODIFICATION 4 : Utilisation de figure et figcaption
    // pour relier sémantiquement la photo à son intitulé.
    echo "<figure>";

    echo "<figcaption>" . $nom . "</figcaption>";

    // MODIFICATION 5 : Conservation de l'attribut alt
    // et suppression de </img>, car img est une balise vide.
    echo "<img class='img-produit'
              src='" . $photo . "'
              alt='" . $nom . "'>";

    echo "</figure>";

    echo "</div>";
}

echo "</div>";
?>
