<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_check_user.php';
require_once __DIR__ . '/../models/EtudiantModel.php';
require_once __DIR__ . '/../models/DocumentModel.php';
require_once __DIR__ . '/../models/UserModel.php';

final class UserController
{
    private EtudiantModel $etudiants;
    private DocumentModel $documents;
    private UserModel $users;

    public function __construct()
    {
        $this->etudiants = new EtudiantModel();
        $this->documents = new DocumentModel();
        $this->users = new UserModel();
    }

    public function profil(): void
    {
        $code = (string)($_SESSION['etudiant_id'] ?? '');
        $etudiant = $this->etudiants->findByCode($code);
        if (!$etudiant) {
            header('Location: index.php?ctrl=auth&action=logout');
            exit();
        }
        $ip = (string)($_SERVER['REMOTE_ADDR'] ?? '');
        $ua = (string)($_SERVER['HTTP_USER_AGENT'] ?? '');
        require __DIR__ . '/../views/user/profil.php';
    }

    public function notes(): void
    {
        $code = (string)($_SESSION['etudiant_id'] ?? '');
        $etudiant = $this->etudiants->findByCode($code);
        if (!$etudiant) {
            header('Location: index.php?ctrl=auth&action=logout');
            exit();
        }
        $classement = $this->etudiants->getClassementFiliere($code);
        require __DIR__ . '/../views/user/notes.php';
    }

    public function documents(): void
    {
        $code = (string)($_SESSION['etudiant_id'] ?? '');
        $documents = $this->documents->findByEtudiant($code);
        require __DIR__ . '/../views/user/documents.php';
    }

    public function changerPassword(): void
    {
        require __DIR__ . '/../views/user/changer_password.php';
    }

    public function changerPasswordTraitement(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header('Location: index.php?ctrl=user&action=changerPassword&msg=method');
            exit();
        }

        $old = (string)($_POST['ancien'] ?? '');
        $new = (string)($_POST['nouveau'] ?? '');
        $conf = (string)($_POST['confirmation'] ?? '');

        if (strlen($new) < 8 || $new !== $conf) {
            header('Location: index.php?ctrl=user&action=changerPassword&msg=validation');
            exit();
        }

        $userId = (int)($_SESSION['user_id'] ?? 0);
        $user = $this->users->findById($userId);
        if (!$user || empty($user['password']) || !password_verify($old, (string)$user['password'])) {
            header('Location: index.php?ctrl=user&action=changerPassword&msg=ancien');
            exit();
        }

        $hash = password_hash($new, PASSWORD_DEFAULT);
        if (!$this->users->updatePassword($userId, $hash)) {
            header('Location: index.php?ctrl=user&action=changerPassword&msg=ko');
            exit();
        }

        header('Location: index.php?ctrl=user&action=profil&msg=pwd_ok');
        exit();
    }
}

