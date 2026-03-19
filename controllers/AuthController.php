<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/UserModel.php';

final class AuthController
{
    private UserModel $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    public function login(): void
    {
        $lastLogin = isset($_COOKIE['last_login']) ? (string)$_COOKIE['last_login'] : '';
        require __DIR__ . '/../views/auth/login.php';
    }

    public function loginTraitement(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header('Location: index.php?ctrl=auth&action=login&erreur=method');
            exit();
        }

        $login = trim((string)($_POST['login'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($login === '' || $password === '') {
            header('Location: index.php?ctrl=auth&action=login&erreur=vide');
            exit();
        }

        $user = $this->users->findByLogin($login);
        if (!$user || empty($user['password']) || !password_verify($password, (string)$user['password'])) {
            header('Location: index.php?ctrl=auth&action=login&erreur=identifiants');
            exit();
        }

        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['login'] = (string)$user['login'];
        $_SESSION['role'] = (string)$user['role'];
        $_SESSION['etudiant_id'] = $user['etudiant_id'] !== null ? (string)$user['etudiant_id'] : null;
        $_SESSION['heure_connexion'] = date('Y-m-d H:i:s');

        setcookie('last_login', (string)$user['login'], time() + (30 * 24 * 60 * 60), "/");
        $this->users->updateDerniereConnexion((int)$user['id']);

        if ($_SESSION['role'] === 'admin') {
            header('Location: index.php?ctrl=etudiant&action=dashboard');
            exit();
        }

        header('Location: index.php?ctrl=user&action=profil');
        exit();
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();

        setcookie('last_login', '', time() - 3600, "/");

        header('Location: index.php?ctrl=auth&action=login&msg=deconnecte');
        exit();
    }
}

