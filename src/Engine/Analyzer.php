<?php

namespace NewSQL\Engine;

use NewSQL\AbstractSyntaxTree\CreateNode;
use NewSQL\AbstractSyntaxTree\DatabaseNode;
use NewSQL\AbstractSyntaxTree\TableNode;
use NewSQL\AbstractSyntaxTree\UseNode;

class Analyzer {
    private Catalog $catalog;

    public function __construct(private Session $session) {
        $this->catalog = new Catalog();
    }


    public function analyze($statement): bool
    {
        if ($statement instanceof UseNode) {
            $database = $statement->database->name();
            if (!$this->session->databaseExists($database)) {
                throw new \Exception("Database '{$database}' does not exist.");
            }
        }
        if ($statement instanceof CreateNode) {
            $id = $statement->id;

            if($id instanceof DatabaseNode) {
                if (!$this->catalog->databaseExists($id->name())) {
                    #throw new \Exception("Database '{$database}' already exists.");
                }
            }
            if($id instanceof TableNode) {
                if (!$this->catalog->tableExists($this->session->currentDatabase(),$id->name())) {
                    #throw new \Exception("Database '{$database}' already exists.");
                }
            }
        }

        return true;
    }
}
