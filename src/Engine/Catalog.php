<?php

namespace NewSQL\Engine;

class Catalog
{
    private string $file;
    private array $data;

    public function __construct(string $filePath = __DIR__ . '/../../catalog.json')
    {
        $this->file = $filePath;

        if (!file_exists($this->file)) {
            file_put_contents($this->file, json_encode(['databases' => []], JSON_PRETTY_PRINT));
        }

        $this->data = json_decode(file_get_contents($this->file), true) ?? ['databases' => []];
    }

    private function save(): void
    {
        file_put_contents($this->file, json_encode($this->data, JSON_PRETTY_PRINT));
    }

    public function databaseExists(string $database): bool
    {
        return array_key_exists($database, $this->data['databases']);
    }

    public function tableExists(string $database, string $table): bool
    {
        if (!isset($this->data['databases'][$database])) {
            return false;
        }

        return in_array($table, $this->data['databases'][$database]['tables'], true);
    }

    public function getDatabases(): array
    {
        return array_keys($this->data['databases']);
    }

    public function addDatabase(string $database): bool
    {
        if ($this->databaseExists($database)) {
            return false;
        }

        $this->data['databases'][$database] = [
            'tables' => []
        ];

        $this->save();
        return true;
    }

    public function getDatabase(string $database): ?array
    {
        return $this->data['databases'][$database] ?? null;
    }

    public function addTable(string $database, string $table): bool
    {
        if (!$this->databaseExists($database)) {
            return false;
        }

        if (in_array($table, $this->data['databases'][$database]['tables'], true)) {
            return false;
        }

        $this->data['databases'][$database]['tables'][] = $table;
        $this->save();
        return true;
    }

    public function getTables(string $database): array
    {
        return $this->data['databases'][$database]['tables'] ?? [];
    }
}
