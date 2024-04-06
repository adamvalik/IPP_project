<?php

namespace IPP\Student;

use DOMElement;
use IPP\Student\Exceptions\XMLStructureException;

abstract class InstructionFactory {

    public static function createInstruction(DOMElement $nodeInstruction, Interpreter $interpret, ExecutionContext $exec, RuntimeEnv $run): Instruction {
        
        $opcode = $nodeInstruction->getAttribute('opcode');
        $namespace = 'IPP\Student\Instructions\\';
        $className = $namespace . "Instruction" . strtoupper($opcode);

        if (class_exists($className) && is_subclass_of($className, Instruction::class)) {
            return new $className($nodeInstruction, $interpret, $exec, $run);
        }
        throw new XMLStructureException("Invalid opcode '$opcode' in XML structure.");
    }
}
