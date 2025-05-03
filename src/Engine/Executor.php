<?php

namespace NewSQL\Engine;

use NewSQL\AbstractSyntaxTree\CreateNode;
use NewSQL\AbstractSyntaxTree\DatabaseNode;
use NewSQL\AbstractSyntaxTree\TableNode;
use NewSQL\AbstractSyntaxTree\UseNode;

class Executor {

    private Catalog $catalog;

    public function __construct(private Session $session) {
        $this->catalog = new Catalog();
    }


    public function execute($statement): bool
    {
        if ($statement instanceof UseNode) {
            $database = $statement->database->name();
            $this->session->setCurrentDatabase($database);
            echo "Switched to database: {$database}\n";
        }
        if ($statement instanceof CreateNode) {
            $id = $statement->id;

            if($id instanceof DatabaseNode) {
                $this->catalog->addDatabase($id->name());
                echo "Database {$id->name()} created\n";
            }
            if($id instanceof TableNode) {
                $this->catalog->addTable($this->session->currentDatabase(), $id->name());
                echo "Table {$id->name()} created\n";
            }

        }
        return true;
    }
}
