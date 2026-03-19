<?php
declare(strict_types=1);
require __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <div>
        <h1>Dashboard <span>Admin</span></h1>
        <small>Heure de connexion : <?= e((string)($_SESSION['heure_connexion'] ?? '')) ?></small>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card"><div class="stat-number"><?= e((string)($stats['total'] ?? 0)) ?></div><div class="stat-label">Total étudiants</div></div>
    <div class="stat-card green"><div class="stat-number"><?= e((string)($stats['recu'] ?? 0)) ?></div><div class="stat-label">Reçus</div></div>
    <div class="stat-card red"><div class="stat-number"><?= e((string)($stats['ajourne'] ?? 0)) ?></div><div class="stat-label">Ajournés</div></div>
    <div class="stat-card orange"><div class="stat-number"><?= e((string)($stats['attente'] ?? 0)) ?></div><div class="stat-label">En attente</div></div>
    <div class="stat-card rose"><div class="stat-number"><?= e((string)($stats['moyenne'] ?? '—')) ?></div><div class="stat-label">Moyenne générale</div></div>
    <div class="stat-card"><div class="stat-number"><?= e((string)$nbDocs) ?></div><div class="stat-label">Documents</div></div>
</div>

<div class="card">
    <div class="card-title">Meilleur étudiant</div>
    <?php if (!empty($stats['meilleur'])): ?>
        <div class="info-grid">
            <div class="info-item"><div class="label">Code</div><div class="value"><?= e((string)$stats['meilleur']['Code']) ?></div></div>
            <div class="info-item"><div class="label">Nom</div><div class="value"><?= e((string)$stats['meilleur']['Nom']) ?> <?= e((string)$stats['meilleur']['Prenom']) ?></div></div>
            <div class="info-item"><div class="label">Note</div><div class="value"><?= e((string)$stats['meilleur']['Note']) ?>/20</div></div>
        </div>
    <?php else: ?>
        <div class="msg-info">Aucune note disponible.</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

