<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';

final class FiliereModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getPDO();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findAll(): array
    {
        $sql = "
            SELECT
                f.CodeF,
                f.IntituleF,
                f.responsable,
                f.nbPlaces,
                COUNT(e.Code) AS NbEtudiants
            FROM filieres f
            LEFT JOIN etudiants e ON e.Filiere = f.CodeF
            GROUP BY f.CodeF, f.IntituleF, f.responsable, f.nbPlaces
            ORDER BY f.IntituleF
        ";
        return $this->pdo->query($sql)->fetchAll();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findByCode(string $code): ?array
    {
        $stmt = $this->pdo->prepare("SELECT CodeF, IntituleF, responsable, nbPlaces FROM filieres WHERE CodeF = :code");
        $stmt->execute([':code' => $code]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function codeExiste(string $code): bool
    {
        $stmt = $this->pdo->prepare("SELECT 1 FROM filieres WHERE CodeF = :code LIMIT 1");
        $stmt->execute([':code' => $code]);
        return (bool)$stmt->fetchColumn();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function insert(array $data): bool
    {
        if ($this->codeExiste((string)$data['CodeF'])) {
            return false;
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO filieres (CodeF, IntituleF, responsable, nbPlaces, created_at)
            VALUES (:CodeF, :IntituleF, :responsable, :nbPlaces, NOW())
        ");
        return $stmt->execute([
            ':CodeF' => (string)$data['CodeF'],
            ':IntituleF' => (string)$data['IntituleF'],
            ':responsable' => (string)$data['responsable'],
            ':nbPlaces' => (int)$data['nbPlaces'],
        ]);
    }
}

