<?php
declare(strict_types=1);
require __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <div><h1>Mes <span>Documents</span></h1></div>
</div>

<div class="card">
    <div class="card-title">PDF disponibles</div>
    <?php if (empty($documents)): ?>
        <div class="msg-info">Aucun document disponible.</div>
    <?php else: ?>
        <?php foreach ($documents as $d): ?>
            <div class="doc-item">
                <div>
                    <div><strong><?= e((string)$d['nom_fichier']) ?></strong></div>
                    <div style="color:#718096;font-size:.85rem;">
                        Type: <?= e((string)$d['type_doc']) ?> —
                        Taille: <?= e((string)round(((int)$d['taille'])/1024, 1)) ?> Ko —
                        Date: <?= e((string)$d['uploaded_at']) ?>
                    </div>
                </div>
                <div>
                    <a class="btn btn-secondary btn-sm" href="<?= e((string)$d['chemin']) ?>" download>Télécharger</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

