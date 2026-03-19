<?php
declare(strict_types=1);
//http://localhost:8000/gestion_upf_mvc/index.php

session_start();

$ctrl = isset($_GET['ctrl']) ? (string)$_GET['ctrl'] : 'auth';
$action = isset($_GET['action']) ? (string)$_GET['action'] : 'login';

$controller = null;

switch ($ctrl) {
    case 'auth':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        break;
    case 'etudiant':
        require_once __DIR__ . '/controllers/EtudiantController.php';
        $controller = new EtudiantController();
        break;
    case 'filiere':
        require_once __DIR__ . '/controllers/FiliereController.php';
        $controller = new FiliereController();
        break;
    case 'user':
        require_once __DIR__ . '/controllers/UserController.php';
        $controller = new UserController();
        break;
    default:
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        $action = 'login';
        break;
}

if (!method_exists($controller, $action)) {
    header('Location: index.php?ctrl=auth&action=login');
    exit();
}

$controller->$action();

