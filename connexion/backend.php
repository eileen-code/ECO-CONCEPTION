<?php
require_once(__DIR__ . "/php/adminMiel.php");
require_once(__DIR__ . "/php/ClassMiel.php");
requireAdminSession();
$csrfToken = adminCsrfToken();
$flash = $_SESSION['admin_flash'] ?? null;
unset($_SESSION['admin_flash']);
$miel = new ClassMiel();
$miels = $miel->getListMiels();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mellifica</title>
        <link href="style.css" rel="stylesheet" />
        <script src="./js/functions.js"></script>
    </head>
    <body>
        <header>
            <img id="logo" src="../img/logo_Mellifica.webp" alt="Logo de Mellifica" />
            <h1 id="titre">Mellifica</h1>
            <img id="gif" src="../gif/abeille.webp" alt="">
        </header>
        <form id="formDeconnexion" action="./php/deconnexion.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
            <button class="bouton" type="submit">Déconnexion</button>
        </form>
        <main>
            <h1 id="soustitre">Gestion des miels</h1>
            <?php if (is_array($flash)): ?>
                <p class="message message-<?= htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8') ?>"
                   role="<?= $flash['type'] === 'error' ? 'alert' : 'status' ?>">
                    <?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>
            <section>               
                <h2>Ajouter un miel</h2>
                <form class="modifyMiel" action="./php/ajouterMiel.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                    <div class="field">
                        <label for="type">Type de miel</label>
                        <input type="text" id="type" name="type" maxlength="255" required>
                    </div>
                    <div class="field">
                        <label for="photo">Photo (JPG, PNG, GIF ou WebP, 5 Mo maximum)</label>
                        <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/gif,image/webp" required>
                    </div>
                    <button class="bouton" type="submit">Ajouter</button>
                </form>
            </section>
            <section>
                <h2>Miels enregistrés</h2>
                <?php if (empty($miels)): ?>
                    <div>Aucun miel enregistré.</div>
                <?php else: ?>
                    <?php foreach ($miels as $unMiel): ?>
                        <article class="miel-item">
                            <img class="miel-photo"
                                 src="../<?= htmlspecialchars($unMiel['photo'], ENT_QUOTES, 'UTF-8') ?>"
                                 alt="Photo du miel <?= htmlspecialchars($unMiel['type'], ENT_QUOTES, 'UTF-8') ?>">
                            <form class="modifyMiel" action="./php/modifierMiel.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="id" value="<?= (int) $unMiel['id'] ?>">
                                <div class="field">
                                    <label for="type-<?= (int) $unMiel['id'] ?>">Type de miel</label>
                                    <input type="text" id="type-<?= (int) $unMiel['id'] ?>" name="type"
                                           value="<?= htmlspecialchars($unMiel['type'], ENT_QUOTES, 'UTF-8') ?>"
                                           maxlength="255" required>
                                </div>
                                <div class="field">
                                    <label for="photo-<?= (int) $unMiel['id'] ?>">Remplacer la photo (facultatif)</label>
                                    <input type="file" id="photo-<?= (int) $unMiel['id'] ?>" name="photo"
                                           accept="image/jpeg,image/png,image/gif,image/webp">
                                </div>
                                <button class="bouton" type="submit">Enregistrer les modifications</button>
                            </form>
                            <form class="delete-form" action="./php/supprimerMiel.php" method="POST"
                                  onsubmit="return confirm('Supprimer ce miel ?');">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="id" value="<?= (int) $unMiel['id'] ?>">
                                <button class="bouton bouton-danger" type="submit">Supprimer</button>
                            </form>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        </main>
    </body>
</html>
