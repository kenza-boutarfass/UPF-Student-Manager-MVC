<?php
declare(strict_types=1);
require __DIR__ . '/../../layout/header.php';

$code = (string)($etudiant['Code'] ?? '');
$photo = (string)($etudiant['Photo'] ?? '');
$msg = (string)($_GET['msg'] ?? '');
?>

<div class="page-header">
    <div><h1>Étudiant <span>Détail</span></h1></div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <a class="btn btn-secondary" href="index.php?ctrl=etudiant&action=liste">Retour</a>
        <a class="btn btn-warning" href="index.php?ctrl=etudiant&action=modifier&code=<?= urlencode($code) ?>">Modifier</a>
    </div>
</div>

<?php if ($msg !== ''): ?>
    <div class="<?= ($msg === 'doc_ok') ? 'msg-success' : 'msg-info' ?>"><?= e($msg) ?></div>
<?php endif; ?>

<div class="profil-header">
    <?php if ($photo !== '' && is_file(__DIR__ . '/../../../' . $photo)): ?>
        <img class="photo-profil" src="<?= e($photo) ?>" alt="Photo">
    <?php else: ?>
        <div class="photo-placeholder">👤</div>
    <?php endif; ?>
    <div class="info">
        <h2><?= e((string)($etudiant['Nom'] ?? '')) ?> <?= e((string)($etudiant['Prenom'] ?? '')) ?></h2>
        <p>Code : <?= e($code) ?> — Filière : <?= e((string)($etudiant['IntituleF'] ?? $etudiant['Filiere'] ?? '')) ?></p>
    </div>
</div>

<div class="card">
    <div class="card-title">Informations</div>
    <div class="info-grid">
        <div class="info-item"><div class="label">Email</div><div class="value"><?= e((string)($etudiant['email'] ?? '')) ?></div></div>
        <div class="info-item"><div class="label">Téléphone</div><div class="value"><?= e((string)($etudiant['telephone'] ?? '')) ?></div></div>
        <div class="info-item"><div class="label">Naissance</div><div class="value"><?= e((string)($etudiant['date_naissance'] ?? '')) ?></div></div>
        <div class="info-item"><div class="label">Note</div><div class="value"><?= ($etudiant['Note'] === null) ? 'Non évalué' : e((string)$etudiant['Note']) . '/20' ?></div></div>
    </div>
</div>

<div class="card">
    <div class="card-title">Documents PDF</div>
    <?php if (empty($documents)): ?>
        <div class="msg-info">Aucun document.</div>
    <?php else: ?>
        <?php foreach ($documents as $d): ?>
            <div class="doc-item">
                <div>
                    <div><strong><?= e((string)$d['nom_fichier']) ?></strong></div>
                    <div style="color:#718096;font-size:.85rem;">
                        Type: <?= e((string)$d['type_doc']) ?> — Taille: <?= e((string)round(((int)$d['taille'])/1024, 1)) ?> Ko — Date: <?= e((string)$d['uploaded_at']) ?>
                    </div>
                </div>
                <div>
                    <a class="btn btn-secondary btn-sm" href="<?= e((string)$d['chemin']) ?>" download>Télécharger</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <hr style="margin:20px 0;border:0;border-top:1px solid #e5e2dd;">
    <div class="card-title">Ajouter un PDF</div>
    <form method="post" action="index.php?ctrl=etudiant&action=detail&code=<?= urlencode($code) ?>" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label>Type</label>
                <select name="type_doc">
                    <option value="releve_notes">releve_notes</option>
                    <option value="attestation">attestation</option>
                    <option value="autre" selected>autre</option>
                </select>
            </div>
            <div class="form-group">
                <label>Fichier PDF</label>
                <input name="document" type="file" accept=".pdf" required>
            </div>
        </div>
        <button class="btn btn-primary" type="submit">Uploader</button>
    </form>
</div>

<?php require __DIR__ . '/../../layout/footer.php'; ?>

