<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';

final class UserModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getPDO();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findByLogin(string $login): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, login, password, role, etudiant_id, derniere_connexion
            FROM utilisateurs
            WHERE login = :login
            LIMIT 1
        ");
        $stmt->execute([':login' => $login]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function updateDerniereConnexion(int $idUser): bool
    {
        $stmt = $this->pdo->prepare("UPDATE utilisateurs SET derniere_connexion = NOW() WHERE id = :id");
        return $stmt->execute([':id' => $idUser]);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findById(int $idUser): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, login, password, role, etudiant_id, derniere_connexion
            FROM utilisateurs
            WHERE id = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => $idUser]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function updatePassword(int $idUser, string $hash): bool
    {
        $stmt = $this->pdo->prepare("UPDATE utilisateurs SET password = :h WHERE id = :id");
        return $stmt->execute([':h' => $hash, ':id' => $idUser]);
    }
}

