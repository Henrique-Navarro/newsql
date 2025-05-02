<?php

namespace NewSQL\AbstractSyntaxTree;

class DatabaseNode {
    public function __construct(public string $name) {}

    public function name(): string { return $this->name; }
}

