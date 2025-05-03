<?php

namespace NewSQL\Token;

enum TokenType: string {
    // Palavras reservadas
    case SELECT = 'SELECT';
    case INSERT = 'INSERT';
    case DELETE = 'DELETE';
    case UPDATE = 'UPDATE';
    case FROM = 'FROM';
    case WHERE = 'WHERE';
    case GROUP_BY = 'GROUP_BY';
    case ORDER_BY = 'ORDER_BY';
    case LIMIT = 'LIMIT';
    case DISTINCT = 'DISTINCT';
    case CREATE = 'CREATE';
    case ALTER = 'ALTER';
    case DROP = 'DROP';
    case TABLE = 'TABLE';
    case DATABASE = 'DATABASE';

    // Condições
    case AND = 'AND';
    case OR = 'OR';
    case NOT = 'NOT';
    case NULL = 'NULL';
    case TRUE = 'TRUE';
    case FALSE = 'FALSE';
    case IF = 'IF';
    case IS = 'IS';
    case MAIOR = '>';
    case MENOR = '<';

    // Operadores
    case MAIS = '+';
    case MENOS = '-';
    case VEZES = 'MULT';
    case DIVIDIR = 'DIV';

    // Outros
    case USE = 'USE';
    case SHOW = 'SHOW';
    case DESCRIBE = 'DESCRIBE';

    // Tipos de dados
    case INT = 'INT';
    case FLOAT = 'FLOAT';
    case TEXT = 'TEXT';
    case BOOL = 'BOOL';

    // Identificador
    case IDENTIFIER = 'IDENTIFIER';

    // Símbolos
    case OPEN_PARENTHESIS = '(';
    case CLOSE_PARENTHESIS = ')';
    case COMMA = ',';
    case SEMICOLON = ';';
    case ASTERISK = '*';
    case EQUAL = '=';

    // Número
    case NUMBER = 'NUMBER';

    // Funções
    case PI = 'PI';
    case CEIL = 'CEIL';
    case FLOOR = 'FLOOR';
    case POW = 'POW';
    case SQRT = 'SQRT';

    // Fim de arquivo
    case EOF = 'EOF';

    public static function get(string $type): ?TokenType
    {
        return self::tryFrom(strtoupper($type));
    }
}
