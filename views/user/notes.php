<?php
declare(strict_types=1);
require __DIR__ . '/../layout/header.php';

$note = $etudiant['Note'] ?? null;
$noteNum = ($note === null) ? null : (float)$note;
$mention = 'Non encore évalué';
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

<div class="page-header">
    <div><h1>Mes <span>Notes</span></h1></div>
</div>

<div class="card">
    <div class="card-title">Résultat</div>
    <div class="info-grid">
        <div class="info-item"><div class="label">Note</div><div class="value"><?= $noteNum === null ? 'Non encore évalué' : e((string)$noteNum) . '/20' ?></div></div>
        <div class="info-item"><div class="label">Mention</div><div class="value"><?= e($mention) ?></div></div>
        <div class="info-item"><div class="label">Statut</div><div class="value"><?= e($statut) ?></div></div>
        <div class="info-item"><div class="label">Rang (filière)</div><div class="value"><?= $classement ? e((string)$classement['rang']) : '—' ?></div></div>
        <div class="info-item"><div class="label">Moyenne filière</div><div class="value"><?= $classement ? e((string)$classement['moyenne_filiere']) : '—' ?></div></div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

