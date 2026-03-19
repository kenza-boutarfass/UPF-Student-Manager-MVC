<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function e(?string $v): string
{
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

$role = (string)($_SESSION['role'] ?? '');
$login = (string)($_SESSION['login'] ?? '');
$currentCtrl = (string)($_GET['ctrl'] ?? '');
$currentAction = (string)($_GET['action'] ?? '');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion UPF</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav class="navbar">
    <a class="navbar-brand" href="index.php"><span>UPF</span> Gestion</a>
    <ul class="navbar-links">
        <?php if ($role === 'admin'): ?>
            <li><a class="<?= ($currentCtrl === 'etudiant' && $currentAction === 'dashboard') ? 'active' : '' ?>" href="index.php?ctrl=etudiant&action=dashboard">Dashboard</a></li>
            <li><a class="<?= ($currentCtrl === 'etudiant' && $currentAction === 'liste') ? 'active' : '' ?>" href="index.php?ctrl=etudiant&action=liste">Etudiants</a></li>
            <li><a class="<?= ($currentCtrl === 'filiere') ? 'active' : '' ?>" href="index.php?ctrl=filiere&action=liste">Filières</a></li>
        <?php elseif ($role === 'user'): ?>
            <li><a class="<?= ($currentCtrl === 'user' && $currentAction === 'profil') ? 'active' : '' ?>" href="index.php?ctrl=user&action=profil">Profil</a></li>
            <li><a class="<?= ($currentCtrl === 'user' && $currentAction === 'notes') ? 'active' : '' ?>" href="index.php?ctrl=user&action=notes">Notes</a></li>
            <li><a class="<?= ($currentCtrl === 'user' && $currentAction === 'documents') ? 'active' : '' ?>" href="index.php?ctrl=user&action=documents">Documents</a></li>
        <?php else: ?>
            <li><a class="<?= ($currentCtrl === 'auth') ? 'active' : '' ?>" href="index.php?ctrl=auth&action=login">Login</a></li>
        <?php endif; ?>
    </ul>
    <div style="display:flex;gap:10px;align-items:center;">
        <?php if ($role !== ''): ?>
            <span class="navbar-user"><?= e($login) ?> (<?= e($role) ?>)</span>
            <a class="btn btn-secondary btn-sm" href="index.php?ctrl=auth&action=logout">Déconnexion</a>
        <?php endif; ?>
    </div>
</nav>
<main class="container">

