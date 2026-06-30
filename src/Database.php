<?php

declare(strict_types=1);

namespace RopaDesk;

final class Database
{
    private \PDO $pdo;

    public function __construct(string $path = 'ropadesk.db')
    {
        $this->pdo = new \PDO('sqlite:' . $path);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->migrate();
    }

    private function migrate(): void
    {
        $this->pdo->exec(<<<'SQL'
CREATE TABLE IF NOT EXISTS activities (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    purpose TEXT NOT NULL,
    legal_basis TEXT NOT NULL,
    data_categories TEXT NOT NULL DEFAULT '',
    data_subjects TEXT NOT NULL DEFAULT '',
    recipients TEXT NOT NULL DEFAULT '',
    third_country TEXT NOT NULL DEFAULT '',
    retention TEXT NOT NULL DEFAULT '',
    security_measures TEXT NOT NULL DEFAULT '',
    locale TEXT NOT NULL DEFAULT 'en',
    created_at TEXT NOT NULL,
    updated_at TEXT NOT NULL
);
SQL);
    }

    public function pdo(): \PDO
    {
        return $this->pdo;
    }
}
