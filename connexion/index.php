<!DOCTYPE html>
<!-- ajout de lang="fr" pour que les lecteurs d'écran lisent la page en français -->
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- titre de page précis pour savoir sur quelle page on se trouve -->
        <title>Connexion administrateur - Mellifica</title>
        <link href="style.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/4.0.0/jquery.min.js"></script>
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
        <main>
            <!-- le titre de la page devient un <h2> (hiérarchie h1 > h2) -->
            <h2 id="soustitre">Connexion</h2>
            <section>
                <form id="formConnexion" action="./php/connexion.php" method="POST">
                    <div>
                        <label for="login">Login (obligatoire) : </label>
                        <!-- required : champ obligatoire annoncé / autocomplete : remplissage automatique -->
                        <input
                            type="text"
                            id="login"
                            name="login"
                            required
                            autocomplete="username"
                        >
                    </div>
                    <div>
                        <label for="motdepasse">Mot de passe (obligatoire) : </label>
                        <input
                            type="password"
                            id="motdepasse"
                            name="motdepasse"
                            required
                            autocomplete="current-password"
                        >
                    </div>
                    <!-- la <div> cliquable devient un vrai bouton d'envoi : utilisable au clavier, sans onclick ni JavaScript -->
                    <button type="submit" class="bouton">Se connecter</button>
                </form>
            </section>
        </main>
    </body>
</html>