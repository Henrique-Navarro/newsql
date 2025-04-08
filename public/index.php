<?php

require __DIR__ . '/../vendor/autoload.php';

use NewSQL\Engine\Lexer;
echo 'Hello, World!' . PHP_EOL;


$query = <<<SQL
    SELECT * FROM tabela;
SQL;

$lexer = new Lexer();

$tokens = $lexer->tokenize($query);

// dd( $query );