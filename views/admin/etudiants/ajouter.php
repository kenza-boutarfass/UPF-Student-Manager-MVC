<?php
declare(strict_types=1);
require __DIR__ . '/../../layout/header.php';
?>

<div class="page-header">
    <div><h1>Étudiants <span>Ajouter</span></h1></div>
    <div><a class="btn btn-secondary" href="index.php?ctrl=etudiant&action=liste">Retour</a></div>
</div>

<div class="card">
    <div class="card-title">Nouveau étudiant</div>
    <form method="post" action="index.php?ctrl=etudiant&action=ajouterTraitement" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label>Code</label>
                <input name="Code" required>
            </div>
            <div class="form-group">
                <label>Filière</label>
                <select name="Filiere">
                    <option value="">-- Choisir --</option>
                    <?php foreach ($filieres as $f): ?>
                        <option value="<?= e((string)$f['CodeF']) ?>"><?= e((string)$f['IntituleF']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Nom</label>
                <input name="Nom" required>
            </div>
            <div class="form-group">
                <label>Prénom</label>
                <input name="Prenom" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Note (/20)</label>
                <input name="Note" type="number" step="0.01" min="0" max="20">
            </div>
            <div class="form-group">
                <label>Date de naissance</label>
                <input name="date_naissance" type="date">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Email</label>
                <input name="email" type="email">
            </div>
            <div class="form-group">
                <label>Téléphone</label>
                <input name="telephone">
            </div>
        </div>
        <div class="form-group">
            <label>Photo (optionnel)</label>
            <input name="photo" type="file" accept=".jpg,.jpeg,.png">
        </div>
        <button class="btn btn-primary" type="submit">Enregistrer</button>
    </form>
</div>

<?php require __DIR__ . '/../../layout/footer.php'; ?>

