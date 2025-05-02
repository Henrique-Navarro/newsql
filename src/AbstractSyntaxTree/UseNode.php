<?php

namespace NewSQL\AbstractSyntaxTree;

class UseNode {
    public DatabaseNode $child;

    public function __construct(public DatabaseNode $database) {
        $this->child = $database;
    }
}
