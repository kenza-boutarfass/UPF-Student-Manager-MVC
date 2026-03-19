<?php
declare(strict_types=1);
require __DIR__ . '/../layout/header.php';

$photo = (string)($etudiant['Photo'] ?? '');
$msg = (string)($_GET['msg'] ?? '');
?>

<div class="page-header">
    <div><h1>Mon <span>Profil</span></h1></div>
    <div><a class="btn btn-secondary" href="index.php?ctrl=user&action=changerPassword">Changer mot de passe</a></div>
</div>

<?php if ($msg === 'pwd_ok'): ?>
    <div class="msg-success">Mot de passe modifié avec succès.</div>
<?php endif; ?>

<div class="profil-header">
    <?php if ($photo !== '' && is_file(__DIR__ . '/../../' . $photo)): ?>
        <img class="photo-profil" src="<?= e($photo) ?>" alt="Photo">
    <?php else: ?>
        <div class="photo-placeholder">👤</div>
    <?php endif; ?>
    <div class="info">
        <h2><?= e((string)($etudiant['Nom'] ?? '')) ?> <?= e((string)($etudiant['Prenom'] ?? '')) ?></h2>
        <p>Filière : <?= e((string)($etudiant['IntituleF'] ?? $etudiant['Filiere'] ?? '')) ?></p>
    </div>
</div>

<div class="card">
    <div class="card-title">Informations</div>
    <div class="info-grid">
        <div class="info-item"><div class="label">Email</div><div class="value"><?= e((string)($etudiant['email'] ?? '')) ?></div></div>
        <div class="info-item"><div class="label">Téléphone</div><div class="value"><?= e((string)($etudiant['telephone'] ?? '')) ?></div></div>
        <div class="info-item"><div class="label">Naissance</div><div class="value"><?= e((string)($etudiant['date_naissance'] ?? '')) ?></div></div>
        <div class="info-item"><div class="label">Connexion</div><div class="value"><?= e((string)($_SESSION['heure_connexion'] ?? '')) ?></div></div>
        <div class="info-item"><div class="label">IP</div><div class="value"><?= e($ip) ?></div></div>
        <div class="info-item"><div class="label">Navigateur</div><div class="value"><?= e($ua) ?></div></div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

