<?php
declare(strict_types=1);
require __DIR__ . '/../../layout/header.php';

$msg = (string)($_GET['msg'] ?? '');
?>

<div class="page-header">
    <div>
        <h1>Étudiants <span>Liste</span></h1>
        <small>Total : <?= e((string)$total) ?></small>
    </div>
    <div>
        <a class="btn btn-primary" href="index.php?ctrl=etudiant&action=ajouter">Ajouter</a>
    </div>
</div>

<?php if ($msg !== ''): ?>
    <div class="<?= str_contains($msg, '_ok') ? 'msg-success' : 'msg-info' ?>">
        <?= e($msg) ?>
    </div>
<?php endif; ?>

<form class="search-bar" method="get" action="index.php">
    <input type="hidden" name="ctrl" value="etudiant">
    <input type="hidden" name="action" value="liste">
    <div class="form-group">
        <label for="recherche">Recherche (nom / prénom)</label>
        <input id="recherche" name="recherche" type="text" value="<?= e($recherche) ?>">
    </div>
    <div class="form-group">
        <label for="filiere">Filière</label>
        <select id="filiere" name="filiere">
            <option value="">-- Toutes --</option>
            <?php foreach ($filieres as $f): ?>
                <option value="<?= e((string)$f['CodeF']) ?>" <?= ((string)$f['CodeF'] === $filiere) ? 'selected' : '' ?>>
                    <?= e((string)$f['IntituleF']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <button class="btn btn-secondary" type="submit">Filtrer</button>
    </div>
</form>

<div class="table-container">
    <table>
        <thead>
        <tr>
            <th>Code</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Filière</th>
            <th>Note</th>
            <th>Mention</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($etudiants as $eRow): ?>
            <?php
            $note = $eRow['Note'];
            $noteNum = ($note === null) ? null : (float)$note;
            $noteClass = 'note-gris';
            if ($noteNum !== null) {
                if ($noteNum >= 12) $noteClass = 'note-vert';
                elseif ($noteNum >= 10) $noteClass = 'note-orange';
                else $noteClass = 'note-rouge';
            }
            $mention = '—';
            $statut = 'En attente';
            if ($noteNum !== null) {
                if ($noteNum >= 16) $mention = 'Très Bien';
                elseif ($noteNum >= 14) $mention = 'Bien';
                elseif ($noteNum >= 12) $mention = 'Assez Bien';
                elseif ($noteNum >= 10) $mention = 'Passable';
                else $mention = 'Insuffisant';
                $statut = ($noteNum >= 10) ? 'Reçu' : 'Ajourné';
            }
            ?>
            <tr>
                <td><?= e((string)$eRow['Code']) ?></td>
                <td><?= e((string)$eRow['Nom']) ?></td>
                <td><?= e((string)$eRow['Prenom']) ?></td>
                <td><?= e((string)($eRow['IntituleF'] ?? $eRow['Filiere'] ?? '')) ?></td>
                <td class="<?= $noteClass ?>"><?= $noteNum === null ? 'Non évalué' : e((string)$noteNum) . '/20' ?></td>
                <td><?= e($mention) ?></td>
                <td><?= e($statut) ?></td>
                <td style="display:flex;gap:8px;flex-wrap:wrap;">
                    <a class="btn btn-info btn-sm" href="index.php?ctrl=etudiant&action=detail&code=<?= urlencode((string)$eRow['Code']) ?>">Voir</a>
                    <a class="btn btn-warning btn-sm" href="index.php?ctrl=etudiant&action=modifier&code=<?= urlencode((string)$eRow['Code']) ?>">Modifier</a>
                    <a class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cet étudiant ?')" href="index.php?ctrl=etudiant&action=supprimer&code=<?= urlencode((string)$eRow['Code']) ?>">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$base = [
    'ctrl' => 'etudiant',
    'action' => 'liste',
    'recherche' => $recherche,
    'filiere' => $filiere,
];
?>
<div class="pagination">
    <?php for ($p = 1; $p <= $nbPages; $p++): ?>
        <?php if ($p === (int)$page): ?>
            <span class="current"><?= $p ?></span>
        <?php else: ?>
            <?php $q = $base; $q['page'] = $p; ?>
            <a href="index.php?<?= e(http_build_query($q)) ?>"><?= $p ?></a>
        <?php endif; ?>
    <?php endfor; ?>
</div>

<?php require __DIR__ . '/../../layout/footer.php'; ?>

