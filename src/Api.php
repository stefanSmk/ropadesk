<?php

declare(strict_types=1);

namespace RopaDesk;

final class Api
{
    private ActivityRepository $repo;
    private string $apiKey;

    public function __construct(?string $dbPath = null, ?string $apiKey = null)
    {
        $this->repo = new ActivityRepository(new Database($dbPath ?? getenv('ROPADESK_DB') ?: 'ropadesk.db'));
        $this->apiKey = $apiKey ?? getenv('ROPADESK_API_KEY') ?: 'change-me';
    }

    public function handle(string $method, string $path): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($path !== '/health' && !$this->authorized()) {
            http_response_code(401);
            echo json_encode(['error' => 'unauthorized']);
            return;
        }

        match (true) {
            $method === 'GET' && $path === '/health' => $this->json(['status' => 'ok']),
            $method === 'GET' && $path === '/api/activities' => $this->json(['activities' => $this->repo->all()]),
            $method === 'GET' && $path === '/api/export' => print($this->repo->exportJson()),
            $method === 'GET' && preg_match('#^/api/activities/(\\d+)$#', $path, $m) === 1 => $this->show((int) $m[1]),
            $method === 'POST' && $path === '/api/activities' => $this->create(),
            $method === 'PUT' && preg_match('#^/api/activities/(\\d+)$#', $path, $m) === 1 => $this->update((int) $m[1]),
            $method === 'DELETE' && preg_match('#^/api/activities/(\\d+)$#', $path, $m) === 1 => $this->delete((int) $m[1]),
            default => $this->error(404, 'not found'),
        };
    }

    private function authorized(): bool
    {
        $key = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        $key = str_starts_with($key, 'Bearer ') ? substr($key, 7) : ($_SERVER['HTTP_X_API_KEY'] ?? '');
        return hash_equals($this->apiKey, $key);
    }

    private function body(): array
    {
        $raw = file_get_contents('php://input') ?: '{}';
        return json_decode($raw, true) ?: [];
    }

    private function create(): void
    {
        $b = $this->body();
        foreach (['name', 'purpose', 'legal_basis'] as $f) {
            if (empty($b[$f])) {
                $this->error(400, "missing field: $f");
                return;
            }
        }
        $this->json($this->repo->create($b), 201);
    }

    private function show(int $id): void
    {
        $row = $this->repo->find($id);
        $row ? $this->json($row) : $this->error(404, 'not found');
    }

    private function update(int $id): void
    {
        $row = $this->repo->update($id, $this->body());
        $row ? $this->json($row) : $this->error(404, 'not found');
    }

    private function delete(int $id): void
    {
        $this->repo->delete($id) ? http_response_code(204) : $this->error(404, 'not found');
    }

    private function json(array $data, int $code = 200): void
    {
        http_response_code($code);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    private function error(int $code, string $msg): void
    {
        http_response_code($code);
        echo json_encode(['error' => $msg]);
    }
}
