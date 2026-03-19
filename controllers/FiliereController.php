<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_check_admin.php';
require_once __DIR__ . '/../models/FiliereModel.php';

final class FiliereController
{
    private FiliereModel $filieres;

    public function __construct()
    {
        $this->filieres = new FiliereModel();
    }

    public function liste(): void
    {
        $filieres = $this->filieres->findAll();
        require __DIR__ . '/../views/admin/filieres/liste.php';
    }

    public function ajouter(): void
    {
        require __DIR__ . '/../views/admin/filieres/ajouter.php';
    }

    public function ajouterTraitement(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header('Location: index.php?ctrl=filiere&action=ajouter&msg=method');
            exit();
        }

        $data = [
            'CodeF' => trim((string)($_POST['CodeF'] ?? '')),
            'IntituleF' => trim((string)($_POST['IntituleF'] ?? '')),
            'responsable' => trim((string)($_POST['responsable'] ?? '')),
            'nbPlaces' => (int)($_POST['nbPlaces'] ?? 0),
        ];

        if ($data['CodeF'] === '' || $data['IntituleF'] === '') {
            header('Location: index.php?ctrl=filiere&action=ajouter&msg=champs');
            exit();
        }

        if ($this->filieres->codeExiste($data['CodeF'])) {
            header('Location: index.php?ctrl=filiere&action=ajouter&msg=existe');
            exit();
        }

        if (!$this->filieres->insert($data)) {
            header('Location: index.php?ctrl=filiere&action=liste&msg=ajout_ko');
            exit();
        }

        header('Location: index.php?ctrl=filiere&action=liste&msg=ajout_ok');
        exit();
    }
}

