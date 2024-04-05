<?php

namespace IPP\Student;

use DOMElement;
use IPP\Student\Exceptions\XMLStructureException;

abstract class InstructionFactory {
    /**
     * @var array<int>
     */
    public static $ordersSeen = [];

    public static function createInstruction(DOMElement $nodeInstruction, Interpreter $interpret): Instruction {
        // XML order validation
        $order = intval($nodeInstruction->getAttribute('order'));
        if (in_array($order, self::$ordersSeen) || $order < 0) {
            throw new XMLStructureException();
        }
        self::$ordersSeen[] = $order;
        
        $opcode = $nodeInstruction->getAttribute('opcode');
        $namespace = 'IPP\Student\Instructions\\';
        $className = $namespace . "Instruction" . strtoupper($opcode);

        if (class_exists($className) && is_subclass_of($className, Instruction::class)) {
            return new $className($nodeInstruction, $interpret);
        }
        throw new XMLStructureException("Invalid opcode '$opcode' in XML structure.");
    }
}
