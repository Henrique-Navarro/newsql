<?php

namespace NewSQL\AbstractSyntaxTree;

class CreateNode {
    public DatabaseNode|TableNode $child;

    public function __construct(public DatabaseNode|TableNode $id) {
        $this->child = $id;
    }
}
