<?php
declare(strict_types=1);
require __DIR__ . '/../../layout/header.php';

$msg = (string)($_GET['msg'] ?? '');
?>

<div class="page-header">
    <div><h1>Filières <span>Liste</span></h1></div>
    <div><a class="btn btn-primary" href="index.php?ctrl=filiere&action=ajouter">Ajouter</a></div>
</div>

<?php if ($msg !== ''): ?>
    <div class="<?= str_contains($msg, '_ok') ? 'msg-success' : 'msg-info' ?>"><?= e($msg) ?></div>
<?php endif; ?>

<div class="table-container">
    <table>
        <thead>
        <tr>
            <th>Code</th>
            <th>Intitulé</th>
            <th>Responsable</th>
            <th>Nb places</th>
            <th>Nb étudiants</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($filieres as $f): ?>
            <tr>
                <td><?= e((string)$f['CodeF']) ?></td>
                <td><?= e((string)$f['IntituleF']) ?></td>
                <td><?= e((string)($f['responsable'] ?? '')) ?></td>
                <td><?= e((string)($f['nbPlaces'] ?? '')) ?></td>
                <td><?= e((string)($f['NbEtudiants'] ?? 0)) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../../layout/footer.php'; ?>

