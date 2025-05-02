<?php

namespace NewSQL\AbstractSyntaxTree;

class FromNode {
    public ?TableNode $child = null;

    public function __construct(public TableNode $table) {
        $this->child = $table;
    }
}
