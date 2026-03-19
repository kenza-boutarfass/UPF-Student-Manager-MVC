<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';

final class EtudiantModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getPDO();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findAll(string $rech = '', string $fil = '', int $limit = PAR_PAGE, int $offset = 0): array
    {
        $where = [];
        $params = [];

        if ($rech !== '') {
            $where[] = "(e.Nom LIKE :rech OR e.Prenom LIKE :rech)";
            $params[':rech'] = '%' . $rech . '%';
        }
        if ($fil !== '') {
            $where[] = "e.Filiere = :fil";
            $params[':fil'] = $fil;
        }

        $sql = "
            SELECT
                e.Code, e.Nom, e.Prenom, e.Filiere, f.IntituleF,
                e.Note, e.date_naissance, e.email, e.telephone, e.Photo
            FROM etudiants e
            LEFT JOIN filieres f ON f.CodeF = e.Filiere
        ";
        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $sql .= " ORDER BY e.Nom, e.Prenom LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function compter(string $rech = '', string $fil = ''): int
    {
        $where = [];
        $params = [];
        if ($rech !== '') {
            $where[] = "(Nom LIKE :rech OR Prenom LIKE :rech)";
            $params[':rech'] = '%' . $rech . '%';
        }
        if ($fil !== '') {
            $where[] = "Filiere = :fil";
            $params[':fil'] = $fil;
        }
        $sql = "SELECT COUNT(*) FROM etudiants";
        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findByCode(string $code): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                e.Code, e.Nom, e.Prenom, e.Filiere, f.IntituleF,
                e.Note, e.date_naissance, e.email, e.telephone, e.Photo
            FROM etudiants e
            LEFT JOIN filieres f ON f.CodeF = e.Filiere
            WHERE e.Code = :code
        ");
        $stmt->execute([':code' => $code]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function insert(array $data): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO etudiants (Code, Nom, Prenom, Filiere, Note, date_naissance, email, telephone, Photo, created_at)
            VALUES (:Code, :Nom, :Prenom, :Filiere, :Note, :date_naissance, :email, :telephone, :Photo, NOW())
        ");

        return $stmt->execute([
            ':Code' => (string)$data['Code'],
            ':Nom' => (string)$data['Nom'],
            ':Prenom' => (string)$data['Prenom'],
            ':Filiere' => (string)$data['Filiere'],
            ':Note' => ($data['Note'] === '' || $data['Note'] === null) ? null : (float)$data['Note'],
            ':date_naissance' => $data['date_naissance'] === '' ? null : (string)$data['date_naissance'],
            ':email' => $data['email'] === '' ? null : (string)$data['email'],
            ':telephone' => $data['telephone'] === '' ? null : (string)$data['telephone'],
            ':Photo' => $data['Photo'] === '' ? null : (string)$data['Photo'],
        ]);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(string $code, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE etudiants
            SET Nom = :Nom,
                Prenom = :Prenom,
                Filiere = :Filiere,
                Note = :Note,
                date_naissance = :date_naissance,
                email = :email,
                telephone = :telephone,
                Photo = :Photo
            WHERE Code = :Code
        ");

        return $stmt->execute([
            ':Code' => $code,
            ':Nom' => (string)$data['Nom'],
            ':Prenom' => (string)$data['Prenom'],
            ':Filiere' => (string)$data['Filiere'],
            ':Note' => ($data['Note'] === '' || $data['Note'] === null) ? null : (float)$data['Note'],
            ':date_naissance' => $data['date_naissance'] === '' ? null : (string)$data['date_naissance'],
            ':email' => $data['email'] === '' ? null : (string)$data['email'],
            ':telephone' => $data['telephone'] === '' ? null : (string)$data['telephone'],
            ':Photo' => $data['Photo'] === '' ? null : (string)$data['Photo'],
        ]);
    }

    public function delete(string $code): bool
    {
        try {
            $this->pdo->beginTransaction();

            // Documents
            $stmt = $this->pdo->prepare("DELETE FROM documents WHERE etudiant_id = :code");
            $stmt->execute([':code' => $code]);

            // Compte utilisateur
            $stmt = $this->pdo->prepare("DELETE FROM utilisateurs WHERE etudiant_id = :code");
            $stmt->execute([':code' => $code]);

            // Etudiant
            $stmt = $this->pdo->prepare("DELETE FROM etudiants WHERE Code = :code");
            $stmt->execute([':code' => $code]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return false;
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function getStatistiques(): array
    {
        $total = (int)$this->pdo->query("SELECT COUNT(*) FROM etudiants")->fetchColumn();
        $recu = (int)$this->pdo->query("SELECT COUNT(*) FROM etudiants WHERE Note IS NOT NULL AND Note >= 10")->fetchColumn();
        $ajourne = (int)$this->pdo->query("SELECT COUNT(*) FROM etudiants WHERE Note IS NOT NULL AND Note < 10")->fetchColumn();
        $attente = (int)$this->pdo->query("SELECT COUNT(*) FROM etudiants WHERE Note IS NULL")->fetchColumn();

        $moyenne = $this->pdo->query("SELECT AVG(Note) FROM etudiants WHERE Note IS NOT NULL")->fetchColumn();
        $moyenne = $moyenne !== null ? round((float)$moyenne, 2) : null;

        $best = $this->pdo->query("SELECT Code, Nom, Prenom, Note FROM etudiants WHERE Note IS NOT NULL ORDER BY Note DESC, Nom ASC LIMIT 1")->fetch();

        return [
            'total' => $total,
            'recu' => $recu,
            'ajourne' => $ajourne,
            'attente' => $attente,
            'moyenne' => $moyenne,
            'meilleur' => $best ?: null,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getClassementFiliere(string $codeE): ?array
    {
        // Récupérer filière + note
        $stmt = $this->pdo->prepare("SELECT Filiere, Note FROM etudiants WHERE Code = :code");
        $stmt->execute([':code' => $codeE]);
        $e = $stmt->fetch();
        if (!$e || $e['Filiere'] === null) {
            return null;
        }

        $codeF = (string)$e['Filiere'];

        // Moyenne filière (sur notes non nulles)
        $stmt = $this->pdo->prepare("SELECT AVG(Note) FROM etudiants WHERE Filiere = :fil AND Note IS NOT NULL");
        $stmt->execute([':fil' => $codeF]);
        $moyF = $stmt->fetchColumn();
        $moyF = $moyF !== null ? round((float)$moyF, 2) : null;

        // Rang (notes non nulles, classement desc)
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) + 1 AS rang
            FROM etudiants
            WHERE Filiere = :fil
              AND Note IS NOT NULL
              AND Note > (SELECT Note FROM etudiants WHERE Code = :code)
        ");
        $stmt->execute([':fil' => $codeF, ':code' => $codeE]);
        $rang = (int)$stmt->fetchColumn();

        return [
            'rang' => $rang,
            'moyenne_filiere' => $moyF,
        ];
    }
}

