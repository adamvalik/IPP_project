<?php

namespace IPP\Student;

use DOMDocument;
use DOMElement;
use DOMAttr;
use IPP\Student\Exceptions\SemanticException;
use IPP\Student\Exceptions\XMLFormatException;
use IPP\Student\Exceptions\XMLStructureException;

abstract class XMLValidator {
    public const string VARIABLE_REGEX = '/^(G|L|T)F@[a-zA-Z_\-$&%*!?][\w_\-$&%*!?]*$/';
    public const string LABEL_REGEX = '/^[a-zA-Z_\-$&%*!?][a-zA-Z0-9_\-$&%*!?]*$/';
    public const string INT_REGEX = '/^(\+|-)?((\d(_?\d)*)|(0[oO][0-7]+)|(0[xX][\da-fA-F]+))$/';
    public const string STRING_REGEX = '/^([^\s\\#]|\\\d{3})*$/';

    /**
     * @var array<int>
     */
    public static $ordersSeen = [];

    /**
     * @var array<string, array<int, string>>
     */
    public static $instructions = [
        'MOVE' => ['var', 'symb'],
        'CREATEFRAME' => [],
        'PUSHFRAME' => [],
        'POPFRAME' => [],
        'DEFVAR' => ['var'],
        'CALL' => ['label'],
        'RETURN' => [],
        'PUSHS' => ['symb'],
        'POPS' => ['var'],
        'ADD' => ['var', 'symb', 'symb'],
        'SUB' => ['var', 'symb', 'symb'],
        'MUL' => ['var', 'symb', 'symb'],
        'IDIV' => ['var', 'symb', 'symb'],
        'LT' => ['var', 'symb', 'symb'],
        'GT' => ['var', 'symb', 'symb'],
        'EQ' => ['var', 'symb', 'symb'],
        'AND' => ['var', 'symb', 'symb'],
        'OR' => ['var', 'symb', 'symb'],
        'NOT' => ['var', 'symb'],
        'INT2CHAR' => ['var', 'symb'],
        'STRI2INT' => ['var', 'symb', 'symb'],
        'READ' => ['var', 'type'],
        'WRITE' => ['symb'],
        'CONCAT' => ['var', 'symb', 'symb'],
        'STRLEN' => ['var', 'symb'],
        'GETCHAR' => ['var', 'symb', 'symb'],
        'SETCHAR' => ['var', 'symb', 'symb'],
        'TYPE' => ['var', 'symb'],
        'LABEL' => ['label'],
        'JUMP' => ['label'],
        'JUMPIFEQ' => ['label', 'symb', 'symb'],
        'JUMPIFNEQ' => ['label', 'symb', 'symb'],
        'EXIT' => ['symb'],
        'DPRINT' => ['symb'],
        'BREAK' => []
    ];

    public static function validateXML(DOMDocument $dom): void {
        $programElement = $dom->documentElement;
        
        // validate the root element <program>
        if ($programElement === null || $programElement->tagName !== 'program') {
            throw new XMLStructureException("Invalid root element");
        }
        // mandatory language attribute
        if (!$programElement->hasAttribute('language') || $programElement->getAttribute('language') !== 'IPPcode24') {
            throw new XMLStructureException("Invalid language attribute or missing");
        }
        // optional attributes
        foreach ($programElement->attributes as $attribute) {
            if ($attribute instanceof DOMAttr && ($attribute->name !== 'language' && $attribute->name !== 'name' && $attribute->name !== 'description')) {
                throw new XMLStructureException("Invalid attribute in root element");
            }
        }

        // validate the instructions
        foreach ($programElement->childNodes as $instrElement) {
            if ($instrElement instanceof DOMElement) {
                if ($instrElement->tagName !== 'instruction') {
                    // <program> can contain only <instruction> elements
                    throw new XMLStructureException("Invalid element in <program> element");
                }
                self::validateInstruction($instrElement);
            }
        }
    }

    public static function validateInstruction(DOMElement $instrElement): void {
        // attributes validation (opcode, order)
        if (!$instrElement->hasAttribute('opcode') || !$instrElement->hasAttribute('order')) {
            throw new XMLStructureException("Missing attributes opcode or order in instruction element");
        }
        if ($instrElement->attributes->length !== 2) {
            throw new XMLStructureException("Only opcode and order attributes are allowed in instruction element");
        }
        // opcode (case-insensitive)
        $opcode = strtoupper($instrElement->getAttribute('opcode'));
        if (!array_key_exists(strtoupper($opcode), self::$instructions)) {
            throw new XMLStructureException("Invalid opcode '$opcode' in instruction element");
        }
        // order
        $order = intval($instrElement->getAttribute('order'));
        if ($order === 0) {
            throw new XMLStructureException("Invalid order in instruction element");
        }
        if (in_array($order, self::$ordersSeen) || $order < 0) {
            throw new XMLStructureException("Duplicit or negative order in instruction element");
        }
        self::$ordersSeen[] = $order;

        // arguments
        $arguments = [];
        foreach ($instrElement->childNodes as $argElement) {
            if ($argElement instanceof DOMElement) {
                // validate argument
                if ($argElement->tagName !== 'arg1' && $argElement->tagName !== 'arg2' && $argElement->tagName !== 'arg3') {
                    throw new XMLStructureException("Invalid element '$argElement->tagName' in instruction element (only arg1, arg2, arg3 are allowed)");
                }
                if (!$argElement->hasAttribute('type')) {
                    throw new XMLStructureException("Missing attribute in argument element");
                }
                if ($argElement->attributes->length !== 1) {
                    throw new XMLStructureException("Only type attribute is allowed in argument element");
                }
                if (!in_array($argElement->getAttribute('type'), ['int', 'bool', 'string', 'nil', 'type', 'label', 'var'])) {
                    throw new XMLStructureException("Invalid type attribute in argument element");
                }
                $arguments[] = $argElement;
            }
        }
        if (count($arguments) !== count(self::$instructions[$opcode])) {
            throw new XMLStructureException("Invalid number of arguments in instruction '$order' '$opcode'");
        }

        // validate the values of arguments based on the instruction non-terminals
        foreach ($arguments as $argElement) {
            $argOrder = intval(substr($argElement->tagName, 3));
            
            // trying to access non-existing index (e.g. arg2 in one-arg instruction)
            if ($argOrder > count(self::$instructions[$opcode])) {
                throw new XMLStructureException("Invalid argument order in instruction '$order' '$opcode'");
            }
            
            $expectedArg = self::$instructions[$opcode][$argOrder - 1];
            $argValue = $argElement->nodeValue;
            $argType = $argElement->getAttribute('type');

            // only string can have empty value as an empty string
            if (($argValue === '' && $argType !== 'string') || $argValue === null) {
                throw new XMLStructureException("Missing value in argument element");
            }

            switch ($expectedArg) {
                case 'var':
                    if ($argType !== 'var') {
                        throw new XMLStructureException("Invalid type attribute in argument element");
                    }
                    if (!preg_match(self::VARIABLE_REGEX, $argValue)) {
                        throw new XMLStructureException("Invalid var value in instruction '$order' '$opcode'");
                    }
                    break;
                case 'symb':
                    if ($argType === 'int') {
                        if (!preg_match(self::INT_REGEX, $argValue)) {
                            throw new XMLStructureException("Invalid int value in instruction '$order' '$opcode'");
                        }
                    }
                    else if ($argType === 'bool') {
                        if (!in_array($argValue, ['true', 'false'])) {
                            throw new XMLStructureException("Invalid bool value in instruction '$order' '$opcode'");
                        }
                    }
                    else if ($argType === 'string') {
                        if (!preg_match(self::STRING_REGEX, $argValue)) {
                            throw new XMLStructureException("Invalid string value in instruction '$order' '$opcode'");
                        }
                    }
                    else if ($argType === 'nil') {
                        if ($argValue !== 'nil') {
                            throw new XMLStructureException("Invalid nil value in instruction '$order' '$opcode'");
                        }
                    }
                    else if ($argType === 'var') {
                        if (!preg_match(self::VARIABLE_REGEX, $argValue)) {
                            throw new XMLStructureException("Invalid var value in instruction '$order' '$opcode'");
                        }
                    }
                    else {
                        throw new XMLStructureException("Invalid type attribute in argument element");
                    }
                    break;
                case 'label':
                    if ($argType !== 'label') {
                        throw new XMLStructureException("Invalid type attribute in argument element");
                    }
                    if (!preg_match(self::LABEL_REGEX, $argValue)) {
                        throw new XMLStructureException("Invalid label value in instruction '$order' '$opcode'");
                    }
                    break;
                case 'type':
                    if ($argType !== 'type') {
                        throw new XMLStructureException("Invalid type attribute in argument element");
                    }
                    if (!in_array($argValue, ['int', 'bool', 'string'])) {
                        throw new XMLStructureException("Invalid type value in instruction '$order' '$opcode'");
                    }
                    break;
            }
        }
    }
}