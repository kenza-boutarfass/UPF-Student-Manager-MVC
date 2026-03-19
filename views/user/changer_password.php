<?php
declare(strict_types=1);
require __DIR__ . '/../layout/header.php';

$msg = (string)($_GET['msg'] ?? '');
?>

<div class="page-header">
    <div><h1>Changer <span>Mot de passe</span></h1></div>
    <div><a class="btn btn-secondary" href="index.php?ctrl=user&action=profil">Retour</a></div>
</div>

<?php if ($msg !== ''): ?>
    <div class="msg-error">
        <?php if ($msg === 'ancien'): ?>
            Ancien mot de passe incorrect.
        <?php elseif ($msg === 'validation'): ?>
            Nouveau mot de passe invalide (min 8) ou confirmation différente.
        <?php else: ?>
            Erreur : <?= e($msg) ?>
        <?php endif; ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-title">Sécurité</div>
    <form method="post" action="index.php?ctrl=user&action=changerPasswordTraitement">
        <div class="form-group">
            <label>Ancien mot de passe</label>
            <input name="ancien" type="password" required>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Nouveau mot de passe</label>
                <input name="nouveau" type="password" minlength="8" required>
            </div>
            <div class="form-group">
                <label>Confirmation</label>
                <input name="confirmation" type="password" minlength="8" required>
            </div>
        </div>
        <button class="btn btn-primary" type="submit">Mettre à jour</button>
    </form>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

