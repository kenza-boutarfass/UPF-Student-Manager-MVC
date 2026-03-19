<?php
declare(strict_types=1);
require __DIR__ . '/../layout/header.php';

$erreur = (string)($_GET['erreur'] ?? '');
$msg = (string)($_GET['msg'] ?? '');
?>

<div class="login-page">
    <div class="login-box">
        <div class="login-logo">
            <h1><span>UPF</span> Gestion</h1>
            <p>Connexion</p>
        </div>

        <?php if ($msg === 'deconnecte'): ?>
            <div class="msg-info">Déconnecté avec succès.</div>
        <?php endif; ?>
        <?php if ($erreur === 'identifiants'): ?>
            <div class="msg-error">Identifiants incorrects.</div>
        <?php elseif ($erreur === 'acces'): ?>
            <div class="msg-error">Accès refusé.</div>
        <?php elseif ($erreur === 'vide'): ?>
            <div class="msg-error">Veuillez remplir tous les champs.</div>
        <?php endif; ?>

        <form method="post" action="index.php?ctrl=auth&action=loginTraitement">
            <div class="form-group">
                <label for="login">Login</label>
                <input id="login" name="login" type="text" value="<?= e($lastLogin ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input id="password" name="password" type="password" required>
            </div>
            <button class="btn btn-primary" type="submit">Se connecter</button>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

