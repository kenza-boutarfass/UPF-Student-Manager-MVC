<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_check_admin.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/EtudiantModel.php';
require_once __DIR__ . '/../models/FiliereModel.php';
require_once __DIR__ . '/../models/DocumentModel.php';

final class EtudiantController
{
    private EtudiantModel $etudiants;
    private FiliereModel $filieres;
    private DocumentModel $documents;

    public function __construct()
    {
        $this->etudiants = new EtudiantModel();
        $this->filieres = new FiliereModel();
        $this->documents = new DocumentModel();
    }

    public function dashboard(): void
    {
        $stats = $this->etudiants->getStatistiques();
        $nbDocs = $this->documents->compter();
        require __DIR__ . '/../views/admin/dashboard.php';
    }

    public function liste(): void
    {
        $recherche = trim((string)($_GET['recherche'] ?? ''));
        $filiere = trim((string)($_GET['filiere'] ?? ''));
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * PAR_PAGE;

        $etudiants = $this->etudiants->findAll($recherche, $filiere, PAR_PAGE, $offset);
        $filieres = $this->filieres->findAll();
        $total = $this->etudiants->compter($recherche, $filiere);
        $nbPages = (int)max(1, (int)ceil($total / PAR_PAGE));

        require __DIR__ . '/../views/admin/etudiants/liste.php';
    }

    public function ajouter(): void
    {
        $filieres = $this->filieres->findAll();
        require __DIR__ . '/../views/admin/etudiants/ajouter.php';
    }

    public function ajouterTraitement(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header('Location: index.php?ctrl=etudiant&action=ajouter&msg=method');
            exit();
        }

        $data = [
            'Code' => trim((string)($_POST['Code'] ?? '')),
            'Nom' => trim((string)($_POST['Nom'] ?? '')),
            'Prenom' => trim((string)($_POST['Prenom'] ?? '')),
            'Filiere' => trim((string)($_POST['Filiere'] ?? '')),
            'Note' => trim((string)($_POST['Note'] ?? '')),
            'date_naissance' => trim((string)($_POST['date_naissance'] ?? '')),
            'email' => trim((string)($_POST['email'] ?? '')),
            'telephone' => trim((string)($_POST['telephone'] ?? '')),
            'Photo' => '',
        ];

        if ($data['Code'] === '' || $data['Nom'] === '' || $data['Prenom'] === '') {
            header('Location: index.php?ctrl=etudiant&action=ajouter&msg=champs');
            exit();
        }

        // Upload photo (optionnel) — 7 étapes
        if (!empty($_FILES['photo']) && (int)($_FILES['photo']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['photo'];

            // 1 Aucune erreur
            if ((int)$file['error'] !== UPLOAD_ERR_OK) {
                header('Location: index.php?ctrl=etudiant&action=ajouter&msg=photo_err');
                exit();
            }
            // 2 Taille max
            if ((int)$file['size'] > MAX_PHOTO) {
                header('Location: index.php?ctrl=etudiant&action=ajouter&msg=photo_taille');
                exit();
            }
            // 3 Extension
            $ext = strtolower((string)pathinfo((string)$file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
                header('Location: index.php?ctrl=etudiant&action=ajouter&msg=photo_ext');
                exit();
            }
            // 4 MIME réel
            $tmp = (string)$file['tmp_name'];
            $mime = @mime_content_type($tmp) ?: '';
            if (!in_array($mime, ['image/jpeg', 'image/png'], true)) {
                header('Location: index.php?ctrl=etudiant&action=ajouter&msg=photo_mime');
                exit();
            }
            // 5 Renommer
            $filename = 'photo_' . $data['Code'] . '.' . $ext;
            $destRel = UPLOAD_PHOTOS . $filename;
            $destAbs = __DIR__ . '/../' . $destRel;
            // 6 Déplacer
            if (!move_uploaded_file($tmp, $destAbs)) {
                header('Location: index.php?ctrl=etudiant&action=ajouter&msg=photo_move');
                exit();
            }
            // 7 Enregistrer chemin en BDD
            $data['Photo'] = $destRel;
        }

        if (!$this->etudiants->insert($data)) {
            header('Location: index.php?ctrl=etudiant&action=liste&msg=ajout_ko');
            exit();
        }

        header('Location: index.php?ctrl=etudiant&action=liste&msg=ajout_ok');
        exit();
    }

    public function modifier(): void
    {
        $code = (string)($_GET['code'] ?? '');
        $etudiant = $this->etudiants->findByCode($code);
        if (!$etudiant) {
            header('Location: index.php?ctrl=etudiant&action=liste&msg=introuvable');
            exit();
        }
        $filieres = $this->filieres->findAll();
        require __DIR__ . '/../views/admin/etudiants/modifier.php';
    }

    public function modifierTraitement(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header('Location: index.php?ctrl=etudiant&action=liste&msg=method');
            exit();
        }

        $code = trim((string)($_POST['Code'] ?? ''));
        $etudiant = $this->etudiants->findByCode($code);
        if (!$etudiant) {
            header('Location: index.php?ctrl=etudiant&action=liste&msg=introuvable');
            exit();
        }

        $data = [
            'Nom' => trim((string)($_POST['Nom'] ?? '')),
            'Prenom' => trim((string)($_POST['Prenom'] ?? '')),
            'Filiere' => trim((string)($_POST['Filiere'] ?? '')),
            'Note' => trim((string)($_POST['Note'] ?? '')),
            'date_naissance' => trim((string)($_POST['date_naissance'] ?? '')),
            'email' => trim((string)($_POST['email'] ?? '')),
            'telephone' => trim((string)($_POST['telephone'] ?? '')),
            'Photo' => (string)($etudiant['Photo'] ?? ''),
        ];

        // Nouvelle photo ?
        if (!empty($_FILES['photo']) && (int)($_FILES['photo']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['photo'];
            if ((int)$file['error'] !== UPLOAD_ERR_OK) {
                header('Location: index.php?ctrl=etudiant&action=modifier&code=' . urlencode($code) . '&msg=photo_err');
                exit();
            }
            if ((int)$file['size'] > MAX_PHOTO) {
                header('Location: index.php?ctrl=etudiant&action=modifier&code=' . urlencode($code) . '&msg=photo_taille');
                exit();
            }
            $ext = strtolower((string)pathinfo((string)$file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
                header('Location: index.php?ctrl=etudiant&action=modifier&code=' . urlencode($code) . '&msg=photo_ext');
                exit();
            }
            $tmp = (string)$file['tmp_name'];
            $mime = @mime_content_type($tmp) ?: '';
            if (!in_array($mime, ['image/jpeg', 'image/png'], true)) {
                header('Location: index.php?ctrl=etudiant&action=modifier&code=' . urlencode($code) . '&msg=photo_mime');
                exit();
            }
            $filename = 'photo_' . $code . '.' . $ext;
            $destRel = UPLOAD_PHOTOS . $filename;
            $destAbs = __DIR__ . '/../' . $destRel;
            if (!move_uploaded_file($tmp, $destAbs)) {
                header('Location: index.php?ctrl=etudiant&action=modifier&code=' . urlencode($code) . '&msg=photo_move');
                exit();
            }
            // unlink ancienne photo si différente
            $old = (string)($etudiant['Photo'] ?? '');
            if ($old !== '' && $old !== $destRel) {
                $oldAbs = __DIR__ . '/../' . $old;
                if (is_file($oldAbs)) {
                    @unlink($oldAbs);
                }
            }
            $data['Photo'] = $destRel;
        }

        if (!$this->etudiants->update($code, $data)) {
            header('Location: index.php?ctrl=etudiant&action=modifier&code=' . urlencode($code) . '&msg=modif_ko');
            exit();
        }

        header('Location: index.php?ctrl=etudiant&action=liste&msg=modif_ok');
        exit();
    }

    public function supprimer(): void
    {
        $code = (string)($_GET['code'] ?? '');
        $etudiant = $this->etudiants->findByCode($code);
        if (!$etudiant) {
            header('Location: index.php?ctrl=etudiant&action=liste&msg=introuvable');
            exit();
        }

        // supprimer fichiers physiques (photo + docs)
        $photo = (string)($etudiant['Photo'] ?? '');
        if ($photo !== '') {
            $p = __DIR__ . '/../' . $photo;
            if (is_file($p)) {
                @unlink($p);
            }
        }
        foreach ($this->documents->findByEtudiant($code) as $doc) {
            $path = (string)($doc['chemin'] ?? '');
            if ($path !== '') {
                $abs = __DIR__ . '/../' . $path;
                if (is_file($abs)) {
                    @unlink($abs);
                }
            }
        }

        if (!$this->etudiants->delete($code)) {
            header('Location: index.php?ctrl=etudiant&action=liste&msg=suppr_ko');
            exit();
        }

        header('Location: index.php?ctrl=etudiant&action=liste&msg=suppr_ok');
        exit();
    }

    public function detail(): void
    {
        $code = (string)($_GET['code'] ?? '');
        $etudiant = $this->etudiants->findByCode($code);
        if (!$etudiant) {
            header('Location: index.php?ctrl=etudiant&action=liste&msg=introuvable');
            exit();
        }

        // Upload PDF (admin) si POST
        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
            $typeDoc = (string)($_POST['type_doc'] ?? 'autre');

            if (empty($_FILES['document']) || (int)($_FILES['document']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
                header('Location: index.php?ctrl=etudiant&action=detail&code=' . urlencode($code) . '&msg=doc_absent');
                exit();
            }

            $file = $_FILES['document'];
            if ((int)$file['error'] !== UPLOAD_ERR_OK) {
                header('Location: index.php?ctrl=etudiant&action=detail&code=' . urlencode($code) . '&msg=doc_err');
                exit();
            }
            if ((int)$file['size'] > MAX_PDF) {
                header('Location: index.php?ctrl=etudiant&action=detail&code=' . urlencode($code) . '&msg=doc_taille');
                exit();
            }
            $ext = strtolower((string)pathinfo((string)$file['name'], PATHINFO_EXTENSION));
            if ($ext !== 'pdf') {
                header('Location: index.php?ctrl=etudiant&action=detail&code=' . urlencode($code) . '&msg=doc_ext');
                exit();
            }
            $tmp = (string)$file['tmp_name'];
            $mime = @mime_content_type($tmp) ?: '';
            if ($mime !== 'application/pdf') {
                header('Location: index.php?ctrl=etudiant&action=detail&code=' . urlencode($code) . '&msg=doc_mime');
                exit();
            }

            $filename = 'doc_' . $code . '_' . time() . '.pdf';
            $destRel = UPLOAD_DOCS . $filename;
            $destAbs = __DIR__ . '/../' . $destRel;
            if (!move_uploaded_file($tmp, $destAbs)) {
                header('Location: index.php?ctrl=etudiant&action=detail&code=' . urlencode($code) . '&msg=doc_move');
                exit();
            }

            $ok = $this->documents->insert([
                'etudiant_id' => $code,
                'type_doc' => $typeDoc,
                'nom_fichier' => (string)$file['name'],
                'chemin' => $destRel,
                'taille' => (int)$file['size'],
                'mime_type' => $mime,
                'uploaded_by' => (int)($_SESSION['user_id'] ?? 0),
            ]);

            if (!$ok) {
                @unlink($destAbs);
                header('Location: index.php?ctrl=etudiant&action=detail&code=' . urlencode($code) . '&msg=doc_db');
                exit();
            }

            header('Location: index.php?ctrl=etudiant&action=detail&code=' . urlencode($code) . '&msg=doc_ok');
            exit();
        }

        $documents = $this->documents->findByEtudiant($code);
        require __DIR__ . '/../views/admin/etudiants/detail.php';
    }
}

