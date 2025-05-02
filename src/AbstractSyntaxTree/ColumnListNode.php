<?php

namespace NewSQL\AbstractSyntaxTree;

class ColumnListNode {
    public ?FromNode $child = null;

    /**
     * @param ColumnNode[] $columns
     */
    public function __construct(public array $columns) {}

    public function toArray(): array
    {
        $array = [];

        foreach ($this->columns as $column) {
            $array[] = $column->name();
        }

        return $array;
    }

    /**
     * @return ColumnNode[] $columns
     */
    public function columns(): array
    {
        return $this->columns;
    }
}
