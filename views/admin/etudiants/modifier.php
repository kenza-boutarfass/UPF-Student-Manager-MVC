<?php
declare(strict_types=1);
require __DIR__ . '/../../layout/header.php';

$code = (string)($etudiant['Code'] ?? '');
$photo = (string)($etudiant['Photo'] ?? '');
?>

<div class="page-header">
    <div><h1>Étudiants <span>Modifier</span></h1></div>
    <div><a class="btn btn-secondary" href="index.php?ctrl=etudiant&action=liste">Retour</a></div>
</div>

<div class="card">
    <div class="card-title">Étudiant <?= e($code) ?></div>
    <form method="post" action="index.php?ctrl=etudiant&action=modifierTraitement" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label>Code</label>
                <input name="Code" value="<?= e($code) ?>" readonly>
            </div>
            <div class="form-group">
                <label>Filière</label>
                <select name="Filiere">
                    <option value="">-- Choisir --</option>
                    <?php foreach ($filieres as $f): ?>
                        <option value="<?= e((string)$f['CodeF']) ?>" <?= ((string)$f['CodeF'] === (string)($etudiant['Filiere'] ?? '')) ? 'selected' : '' ?>>
                            <?= e((string)$f['IntituleF']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Nom</label>
                <input name="Nom" value="<?= e((string)($etudiant['Nom'] ?? '')) ?>" required>
            </div>
            <div class="form-group">
                <label>Prénom</label>
                <input name="Prenom" value="<?= e((string)($etudiant['Prenom'] ?? '')) ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Note (/20)</label>
                <input name="Note" type="number" step="0.01" min="0" max="20" value="<?= e((string)($etudiant['Note'] ?? '')) ?>">
            </div>
            <div class="form-group">
                <label>Date de naissance</label>
                <input name="date_naissance" type="date" value="<?= e((string)($etudiant['date_naissance'] ?? '')) ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Email</label>
                <input name="email" type="email" value="<?= e((string)($etudiant['email'] ?? '')) ?>">
            </div>
            <div class="form-group">
                <label>Téléphone</label>
                <input name="telephone" value="<?= e((string)($etudiant['telephone'] ?? '')) ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Photo actuelle</label>
            <?php if ($photo !== '' && is_file(__DIR__ . '/../../../' . $photo)): ?>
                <img class="photo-profil" src="<?= e($photo) ?>" alt="Photo">
            <?php else: ?>
                <div class="photo-placeholder">👤</div>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label>Nouvelle photo (optionnel)</label>
            <input name="photo" type="file" accept=".jpg,.jpeg,.png">
        </div>
        <button class="btn btn-primary" type="submit">Enregistrer</button>
    </form>
</div>

<?php require __DIR__ . '/../../layout/footer.php'; ?>

