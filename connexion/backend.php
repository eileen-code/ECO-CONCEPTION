<!DOCTYPE html >
<?php
    session_start();
    if(!isset($_SESSION['user'])){
        header('Location: ../index.php');
        exit;
    }else{
        require_once("./php/ClassMiel.php");
        $miel = new ClassMiel();
        $miels = $miel->getListMiels();
    }
?>
<!-- Ajout de la langue du document -->
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mellifica</title>
        <link href="style.css" rel="stylesheet" />
        <script src="./js/functions.js"></script>
    </head>
    <body>
        <header>
            <img id="logo" src="img/logo_Mellifica.png" alt="Logo de Mellifica" />
            <div id="titre">Mellifica</div>
            <!-- Ajout de alt pour l'image gif -->
            <img id="gif" src="./gif/abeille.gif" alt="Gif d'une abeille en mouvement">
        </header>
        <form id="formDeconnexion" action="./php/deconnexion.php" method="GET">
            <!-- Modification du bouton div en button pour respecter les normes d'accessibilité et de sémantique HTML -->
                <button class="bouton" type="submit">Déconnexion</button>
            </form>
        <main>
            <div id="soustitre">Connexion</div>
<!-- Ajout Miel -->             
            <section>               
                <p>Ajouter Miel</p>
                <form class="modifyMiel" action="./php/ajouterMiel.php" method="POST">
                    <label for="type">Type de miel :</label>
                    <input type="text" id="type" name="type">

                    <label for="photo">Chemin de la photo :</label>
                    <input type="text" id="photo" name="photo">
                    <!-- Modification du bouton div en button pour respecter les normes d'accessibilité et de sémantique HTML -->
                    <button class="bouton" type="submit">Ajouter</button>
                </form>
            </section>
<!-- Suppression Miel -->              
            <section>
                <p>Supprimer</p>
                <?php if (empty($miels)): ?>
                    <div>Aucun miel enregistré.</div>
                <?php else: ?>
                    <?php foreach ($miels as $unMiel): ?>
                        <div class="modifyMiel">
                            <span>
                                <?= htmlspecialchars(
                                    $unMiel['type'] ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </span>
                            <span>
                                Identifiant :
                                <?= (int) $unMiel['id'] ?>
                            </span>
                            <form action="./php/supprimerMiel.php" method="POST">
                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= (int) $unMiel['id'] ?>"
                                >
                                <!-- Modification du bouton div en button pour respecter les normes d'accessibilité et de sémantique HTML -->
                                <button class="bouton" type="submit">Supprimer</button>
                            </form>
                        </div>
                        <hr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        </main>
    </body>
</html>
