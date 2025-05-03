<?php

namespace NewSQL\Engine;

class Session
{
    // remover hardcode e manipular com create (posso escrever em um arquivo)
    private array $databases = ['db1', 'db2', 'db3'];
    private ?string $currentDatabase = null;
    private Catalog $catalog;

    public function __construct() {
        $this->catalog = new Catalog();
    }

    public function databaseExists(string $database): bool
    {
        return $this->catalog->databaseExists($database);
    }

    public function setCurrentDatabase(string $database): void
    {
        $this->currentDatabase = $database;
    }

    public function currentDatabase(): ?string
    {
        return $this->currentDatabase;
    }
}
