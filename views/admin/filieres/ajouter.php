<?php
declare(strict_types=1);
require __DIR__ . '/../../layout/header.php';
?>

<div class="page-header">
    <div><h1>Filières <span>Ajouter</span></h1></div>
    <div><a class="btn btn-secondary" href="index.php?ctrl=filiere&action=liste">Retour</a></div>
</div>

<div class="card">
    <div class="card-title">Nouvelle filière</div>
    <form method="post" action="index.php?ctrl=filiere&action=ajouterTraitement">
        <div class="form-row">
            <div class="form-group">
                <label>Code</label>
                <input name="CodeF" required>
            </div>
            <div class="form-group">
                <label>Nb places</label>
                <input name="nbPlaces" type="number" min="0" value="0">
            </div>
        </div>
        <div class="form-group">
            <label>Intitulé</label>
            <input name="IntituleF" required>
        </div>
        <div class="form-group">
            <label>Responsable</label>
            <input name="responsable">
        </div>
        <button class="btn btn-primary" type="submit">Enregistrer</button>
    </form>
</div>

<?php require __DIR__ . '/../../layout/footer.php'; ?>

