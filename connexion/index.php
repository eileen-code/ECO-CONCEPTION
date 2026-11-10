<!DOCTYPE html >
<!-- Ajout de la langue du document -->
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mellifica</title>
        <link href="style.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/4.0.0/jquery.min.js"></script>
        <script src="./js/functions.js"></script>
    </head>
    <body>
        <header>
            <img id="logo" src="img/logo_Mellifica.png" alt="Logo de Mellifica" />
            <div id="titre">Mellifica</div>
            <!-- Ajout de alt pour l'image gif -->
            <img id="gif" src="./gif/abeille.gif" alt="">
        </header>
        <main>
            <div id="soustitre">Connexion</div>
            <section>
                <form id="formConnexion" action="./php/connexion.php" method="POST">
                    <div>
                        <label for="login">Login: </label>
                        <input
                            type="text"
                            id="login"
                            name="login"
                        >
                    </div>
                    <div>
                        <label for="motdepasse">Mot de passe: </label>
                        <input
                            type="password"
                            id="motdepasse"
                            name="motdepasse"
                        >
                    </div>
                    <!-- Modification du bouton div en button pour respecter les normes d'accessibilité et de sémantique HTML -->
                    <button class="bouton" type="submit"> Se connecter</button>
                </form>
            </section>
        </main>
    </body>
</html>
