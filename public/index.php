<?php

require __DIR__ . '/../vendor/autoload.php';

use NewSQL\Engine\Analyzer;
use NewSQL\Engine\Executor;
use NewSQL\Engine\Lexer;
use NewSQL\Engine\Parser;
use NewSQL\Engine\Session;
use NewSQL\Token\TokenType;

$query = $argv[0];
$session = new Session();

// se tiver algo depois do ;, erro sintático
$query = <<<SQL
SELECT DISTINCT col1,col2,col3, col4 FROM tabela WHERE col = 1;
SQL;
$query = "USE db1;";

$query = "CREATE DATABASE meubanco2;USE meubanco;CREATE TABLE tabela;";
// $query = "CREATE TABLE";
echo $query  . PHP_EOL . PHP_EOL;

$lexer = new Lexer();
$tokens = $lexer->tokenize($query);

$parser = new Parser();
$statements = $parser->parse($tokens);

$analyzer = new Analyzer($session);
$executor = new Executor($session);

foreach ($statements as $statement) {
    $analyzer->analyze($statement);
    $executor->execute($statement);
}

dd($session);