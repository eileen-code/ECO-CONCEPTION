<?php
require_once(__DIR__ . "/php/adminMiel.php");
startSecureSession();
$csrfToken = adminCsrfToken();
$loginError = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mellifica</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <img id="logo" src="../img/logo_Mellifica.webp" alt="Logo de Mellifica" />
            <h1 id="titre">Mellifica</h1>
            <img id="gif" src="../gif/abeille.webp" alt="">
        </header>
        <main>
    <section aria-labelledby="soustitre">
        <h2 id="soustitre">Connexion</h2>

        <form id="formConnexion" action="./php/connexion.php" method="POST">

            <!-- Protection CSRF du formulaire. -->
            <input type="hidden" name="csrf_token"
                   value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
            <?php if ($loginError !== ''): ?>
                <p id="erreur" class="erreur" role="alert">
                    <?= htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>

            <div>
                <label for="login">Login : </label>
                <input type="text" id="login" name="login"
                       maxlength="255" required autocomplete="username"
                       autocapitalize="none" spellcheck="false">
            </div>

            <div>
                <label for="motdepasse">Mot de passe : </label>
                <input type="password" id="motdepasse" name="motdepasse"
                       maxlength="1024" required autocomplete="current-password">
            </div>

            <button type="submit" class="bouton">Se connecter</button>
        </form>
    </section>
</main>
    </body>
</html>
