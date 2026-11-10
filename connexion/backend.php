<!DOCTYPE html>
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
<!-- ajout de lang="fr" pour que les lecteurs d'écran lisent la page en français -->
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- titre de page précis pour savoir sur quelle page on se trouve -->
        <title>Administration - Mellifica</title>
        <link href="style.css" rel="stylesheet" />
        <script src="./js/functions.js"></script>
    </head>
    <body>
        <header>
            <img id="logo" src="img/logo_Mellifica.png" alt="Logo de Mellifica" />
            <!-- le titre du site devient un vrai titre <h1> au lieu d'une <div> -->
            <h1 id="titre">Mellifica</h1>
            <!-- ajout de alt="" : l'abeille est décorative, elle est ignorée par les lecteurs d'écran -->
            <img id="gif" src="./gif/abeille.gif" alt="">
        </header>
        <form id="formDeconnexion" action="./php/deconnexion.php" method="GET">
            <!-- la <div> cliquable devient un vrai bouton d'envoi : utilisable au clavier, sans onclick ni JavaScript -->
            <button type="submit" class="bouton">Déconnexion</button>
        </form>
        <main>
            <!-- titre <h2> corrigé : la page affichait "Connexion" alors qu'on est sur l'administration -->
            <h2 id="soustitre">Administration</h2>
<!-- Ajout Miel -->
            <section>
                <!-- le titre de section devient un <h3> au lieu d'un <p> (hiérarchie h1 > h2 > h3) -->
                <h3>Ajouter un miel</h3>
                <form class="modifyMiel" action="./php/ajouterMiel.php" method="POST">
                    <!-- required : champ obligatoire annoncé, le formulaire ne part pas vide -->
                    <label for="type">Type de miel (obligatoire) :</label>
                    <input type="text" id="type" name="type" required>

                    <label for="photo">Chemin de la photo (obligatoire) :</label>
                    <input type="text" id="photo" name="photo" required>

                    <!-- vrai bouton d'envoi à la place de la <div> cliquable -->
                    <button type="submit" class="bouton">Ajouter</button>
                </form>
            </section>
<!-- Suppression Miel -->
            <section>
                <!-- le titre de section devient un <h3> au lieu d'un <p> -->
                <h3>Supprimer un miel</h3>
                <?php if (empty($miels)): ?>
                    <p>Aucun miel enregistré</p>
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
                            <!-- suppression de l'affichage "Identifiant : X" : donnée interne inutile pour l'utilisateur
                                 l'id reste transmis dans le champ caché du formulaire -->
                            <form action="./php/supprimerMiel.php" method="POST">
                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= (int) $unMiel['id'] ?>"
                                >
                                <!-- vrai bouton d'envoi + aria-label : le lecteur d'écran annonce quel miel est supprimé
                                     au lieu de répéter "Supprimer" pour chaque ligne -->
                                <button type="submit" class="bouton" aria-label="Supprimer le miel <?= htmlspecialchars($unMiel['type'] ?? '', ENT_QUOTES, 'UTF-8') ?>">Supprimer</button>
                            </form>
                        </div>
                        <hr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        </main>
    </body>
</html>