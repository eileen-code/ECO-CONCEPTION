<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mellifica</title>
        <link rel="stylesheet" href="style.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/4.0.0/jquery.min.js"></script>
        <script src="./js/functions.js"></script>
    </head>
    <body>
        <header>
            <img id="logo" src="img/logo_Mellifica.png" alt="Logo de Mellifica" >
            <div id="titre">Mellifica</div>
            <img id="gif" src="./gif/abeille.gif" alt="">
        </header>
        <main>
    <section aria-labelledby="soustitre">
        <h2 id="soustitre">Connexion</h2>

        <form id="formConnexion" action="./php/connexion.php" method="POST"
              onsubmit="envoiFormulaire(this); return false;">

            <!-- Zone des messages d'erreur, lue automatiquement par le lecteur d'écran -->
            <p id="erreur" class="erreur" role="alert"></p>

            <div>
                <label for="login">Login : </label>
                <input type="text" id="login" name="login"
                       required autocomplete="username">
            </div>

            <div>
                <label for="motdepasse">Mot de passe : </label>
                <input type="password" id="motdepasse" name="motdepasse"
                       required autocomplete="current-password">
            </div>

            <button type="submit" class="bouton">Se connecter</button>
        </form>
    </section>
</main>
    </body>
</html>
