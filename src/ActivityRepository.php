<?php

declare(strict_types=1);

namespace RopaDesk;

final class ActivityRepository
{
    public function __construct(private Database $db) {}

    /** @return list<array<string, mixed>> */
    public function all(): array
    {
        $stmt = $this->db->pdo()->query('SELECT * FROM activities ORDER BY updated_at DESC');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->pdo()->prepare('SELECT * FROM activities WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /** @param array<string, string> $data */
    public function create(array $data): array
    {
        $now = gmdate('c');
        $stmt = $this->db->pdo()->prepare(<<<'SQL'
INSERT INTO activities (name, purpose, legal_basis, data_categories, data_subjects, recipients, third_country, retention, security_measures, locale, created_at, updated_at)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
SQL);
        $stmt->execute([
            $data['name'], $data['purpose'], $data['legal_basis'],
            $data['data_categories'] ?? '', $data['data_subjects'] ?? '',
            $data['recipients'] ?? '', $data['third_country'] ?? '',
            $data['retention'] ?? '', $data['security_measures'] ?? '',
            $data['locale'] ?? 'en', $now, $now,
        ]);
        return $this->find((int) $this->db->pdo()->lastInsertId()) ?? [];
    }

    /** @param array<string, string> $data */
    public function update(int $id, array $data): ?array
    {
        if ($this->find($id) === null) {
            return null;
        }
        $now = gmdate('c');
        $stmt = $this->db->pdo()->prepare(<<<'SQL'
UPDATE activities SET name=?, purpose=?, legal_basis=?, data_categories=?, data_subjects=?, recipients=?, third_country=?, retention=?, security_measures=?, locale=?, updated_at=? WHERE id=?
SQL);
        $stmt->execute([
            $data['name'], $data['purpose'], $data['legal_basis'],
            $data['data_categories'] ?? '', $data['data_subjects'] ?? '',
            $data['recipients'] ?? '', $data['third_country'] ?? '',
            $data['retention'] ?? '', $data['security_measures'] ?? '',
            $data['locale'] ?? 'en', $now, $id,
        ]);
        return $this->find($id);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->pdo()->prepare('DELETE FROM activities WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    public function exportJson(): string
    {
        return json_encode(['activities' => $this->all(), 'exported_at' => gmdate('c')], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
