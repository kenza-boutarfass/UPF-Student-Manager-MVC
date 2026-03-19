<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';

final class DocumentModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getPDO();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findByEtudiant(string $codeE): array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, etudiant_id, type_doc, nom_fichier, chemin, taille, mime_type, uploaded_by, uploaded_at
            FROM documents
            WHERE etudiant_id = :code
            ORDER BY uploaded_at DESC, id DESC
        ");
        $stmt->execute([':code' => $codeE]);
        return $stmt->fetchAll();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function insert(array $data): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO documents (etudiant_id, type_doc, nom_fichier, chemin, taille, mime_type, uploaded_by, uploaded_at)
            VALUES (:etudiant_id, :type_doc, :nom_fichier, :chemin, :taille, :mime_type, :uploaded_by, NOW())
        ");
        return $stmt->execute([
            ':etudiant_id' => (string)$data['etudiant_id'],
            ':type_doc' => (string)$data['type_doc'],
            ':nom_fichier' => (string)$data['nom_fichier'],
            ':chemin' => (string)$data['chemin'],
            ':taille' => (int)$data['taille'],
            ':mime_type' => (string)$data['mime_type'],
            ':uploaded_by' => (int)$data['uploaded_by'],
        ]);
    }

    public function compter(): int
    {
        return (int)$this->pdo->query("SELECT COUNT(*) FROM documents")->fetchColumn();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM documents WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}

