<?php

namespace NewSQL\AbstractSyntaxTree;

class TableNode {
    public ?object $child = null; // Pode ser JoinNode, ConditionNode, AliasNode, etc.

    public function __construct(public string $name) {}

    public function name(): string {
        return $this->name;
    }

    public function setChild(object $child): void {
        $this->child = $child;
    }

    public function getChild(): ?object {
        return $this->child;
    }
}
