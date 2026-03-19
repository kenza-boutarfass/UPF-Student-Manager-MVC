<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (
    empty($_SESSION['user_id'])
    || empty($_SESSION['role'])
    || $_SESSION['role'] !== 'user'
) {
    header('Location: index.php?ctrl=auth&action=login&erreur=acces');
    exit();
}

